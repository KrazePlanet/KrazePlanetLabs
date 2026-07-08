"""
HackerOne Report Scraper
Scrapes any public HackerOne report and saves it as a markdown file.

Usage:
    python3 hackerone_scraper.py <report_url>
    python3 hackerone_scraper.py https://hackerone.com/reports/1818163

Requirements:
    pip install undetected-chromedriver selenium webdriver-manager --break-system-packages
"""

import sys
import os
import re
import time
import argparse

# Try undetected-chromedriver first (bypasses bot detection), fallback to regular selenium
try:
    import undetected_chromedriver as uc
    USE_UC = True
    print("[*] Using undetected-chromedriver")
except ImportError:
    from selenium import webdriver
    from selenium.webdriver.chrome.options import Options
    from selenium.webdriver.chrome.service import Service
    try:
        from webdriver_manager.chrome import ChromeDriverManager
        USE_WDM = True
    except ImportError:
        USE_WDM = False
    USE_UC = False
    print("[*] Using standard selenium (undetected-chromedriver not found)")

from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException, WebDriverException


# Chrome major version installed in WSL
CHROME_VERSION = 147


def get_driver(headless=True):
    """Initialize Chrome WebDriver with anti-detection options.
    Options object is created fresh every call to avoid 'cannot reuse' error.
    """
    if USE_UC:
        # Build fresh options object every time (uc does not allow reuse)
        options = uc.ChromeOptions()
        options.add_argument("--no-sandbox")
        options.add_argument("--disable-dev-shm-usage")
        options.add_argument("--disable-gpu")
        options.add_argument("--window-size=1920,1080")
        options.add_argument("--disable-blink-features=AutomationControlled")
        options.add_argument("--disable-infobars")
        options.add_argument("--lang=en-US,en;q=0.9")
        if headless:
            options.add_argument("--headless=new")

        driver = uc.Chrome(
            options=options,
            use_subprocess=True,
            version_main=CHROME_VERSION,   # pin to installed Chrome version
        )
        return driver

    else:
        # Standard Selenium fallback
        from selenium import webdriver
        from selenium.webdriver.chrome.options import Options
        options = Options()
        options.add_argument("--no-sandbox")
        options.add_argument("--disable-dev-shm-usage")
        options.add_argument("--disable-gpu")
        options.add_argument("--window-size=1920,1080")
        options.add_argument("--disable-blink-features=AutomationControlled")
        options.add_experimental_option("excludeSwitches", ["enable-automation"])
        options.add_experimental_option("useAutomationExtension", False)
        options.add_argument(
            "user-agent=Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 "
            f"(KHTML, like Gecko) Chrome/{CHROME_VERSION}.0.0.0 Safari/537.36"
        )
        if headless:
            options.add_argument("--headless=new")

        if USE_WDM:
            from webdriver_manager.chrome import ChromeDriverManager
            from selenium.webdriver.chrome.service import Service
            service = Service(ChromeDriverManager().install())
            driver = webdriver.Chrome(service=service, options=options)
        else:
            driver = webdriver.Chrome(options=options)

        driver.execute_script("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})")
        return driver


def wait_for_page_ready(driver, timeout=30):
    """Wait until the page is fully loaded and not showing a Cloudflare/bot challenge."""
    print("[*] Waiting for page to load...")

    # First: wait for document.readyState == complete
    WebDriverWait(driver, timeout).until(
        lambda d: d.execute_script("return document.readyState") == "complete"
    )

    # Second: detect Cloudflare challenge
    for _ in range(20):
        title = driver.title.lower()
        page_src = driver.page_source.lower()
        if "just a moment" in title or "cloudflare" in page_src and "checking" in page_src:
            print("[*] Cloudflare challenge detected, waiting 3s...")
            time.sleep(3)
        else:
            break

    # Third: wait for any h1 OR the content wrapper
    try:
        WebDriverWait(driver, timeout).until(
            lambda d: (
                len(d.find_elements(By.TAG_NAME, "h1")) > 0
                or len(d.find_elements(By.CSS_SELECTOR, "[class*='content-wrapper']")) > 0
                or len(d.find_elements(By.CSS_SELECTOR, "[class*='report']")) > 0
            )
        )
    except TimeoutException:
        print("[!] Timed out waiting for page content. Dumping page title for debug:")
        print(f"    Title: {driver.title}")
        print(f"    URL: {driver.current_url}")
        # Save debug HTML
        with open("debug_page.html", "w", encoding="utf-8") as f:
            f.write(driver.page_source)
        print("[!] Saved page source to debug_page.html for inspection")
        raise


def scroll_to_bottom(driver, pause=1.5, max_scrolls=20):
    """Scroll the page to the bottom to trigger lazy loading."""
    print("[*] Scrolling to load all content...")
    last_height = driver.execute_script("return document.body.scrollHeight")
    scrolls = 0
    while scrolls < max_scrolls:
        driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(pause)
        new_height = driver.execute_script("return document.body.scrollHeight")
        if new_height == last_height:
            break
        last_height = new_height
        scrolls += 1
    # Scroll back to top
    driver.execute_script("window.scrollTo(0, 0);")
    time.sleep(0.5)


def click_show_more(driver):
    """Click any 'Show more' / 'Load more' buttons to expand collapsed content."""
    keywords = [
        "show more", "load more", "view more", "show all",
        "expand", "see more", "show full"
    ]
    for kw in keywords:
        try:
            btns = driver.find_elements(
                By.XPATH,
                f"//*[translate(normalize-space(text()), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', "
                f"'abcdefghijklmnopqrstuvwxyz')='{kw}']"
            )
            for btn in btns:
                try:
                    driver.execute_script("arguments[0].scrollIntoView(true);", btn)
                    driver.execute_script("arguments[0].click();", btn)
                    time.sleep(0.5)
                except Exception:
                    pass
        except Exception:
            pass


def get_text_safe(element):
    try:
        return element.text.strip()
    except Exception:
        return ""


def get_attr_safe(element, attr):
    try:
        return element.get_attribute(attr) or ""
    except Exception:
        return ""


def find_content_wrapper(driver):
    """Find the main content wrapper element."""
    selectors = [
        ".content-wrapper.da-scroll-area.card-drop-shadow",
        ".content-wrapper.da-scroll-area",
        "[class*='content-wrapper'][class*='da-scroll']",
        "[class*='content-wrapper']",
        "main",
        "article",
        "[role='main']",
    ]
    for sel in selectors:
        try:
            el = driver.find_element(By.CSS_SELECTOR, sel)
            if el and el.text.strip():
                print(f"[+] Found content using selector: {sel}")
                return el
        except NoSuchElementException:
            continue
    # Last resort: body
    print("[!] Could not find content-wrapper, using body")
    return driver.find_element(By.TAG_NAME, "body")


def extract_images(content_div, driver):
    """Extract all image URLs from the content area."""
    images = []
    seen = set()

    # Direct <img> tags
    for img in content_div.find_elements(By.TAG_NAME, "img"):
        for attr in ["src", "data-src", "data-lazy-src"]:
            src = get_attr_safe(img, attr)
            if src and src not in seen and not src.endswith(".svg"):
                # Skip tiny icons/avatars
                width = get_attr_safe(img, "width")
                if width and int(width) < 20 if width.isdigit() else False:
                    continue
                seen.add(src)
                alt = get_attr_safe(img, "alt") or ""
                images.append({"src": src, "alt": alt})

    # Background images via JS
    try:
        bg_imgs = driver.execute_script("""
            var imgs = [];
            var els = arguments[0].querySelectorAll('*');
            for (var el of els) {
                var bg = window.getComputedStyle(el).backgroundImage;
                if (bg && bg !== 'none' && bg.includes('http')) {
                    var match = bg.match(/url\\(["']?([^"')]+)["']?\\)/);
                    if (match) imgs.push(match[1]);
                }
            }
            return imgs;
        """, content_div)
        for src in (bg_imgs or []):
            if src not in seen:
                seen.add(src)
                images.append({"src": src, "alt": ""})
    except Exception:
        pass

    return images


def extract_videos(content_div):
    """Extract all video URLs from the content area."""
    videos = []
    seen = set()

    for tag in ["video", "source"]:
        for el in content_div.find_elements(By.TAG_NAME, tag):
            src = get_attr_safe(el, "src")
            if src and src not in seen:
                seen.add(src)
                videos.append(src)

    # <a> tags pointing to video files
    for a in content_div.find_elements(By.TAG_NAME, "a"):
        href = get_attr_safe(a, "href")
        if href and href not in seen:
            if any(ext in href.lower() for ext in [".mp4", ".webm", ".mov", ".avi", ".mkv"]):
                seen.add(href)
                text = get_text_safe(a)
                videos.append(href)

    return videos


def extract_links(content_div):
    """Extract all hyperlinks from the content area."""
    links = []
    seen = set()
    for a in content_div.find_elements(By.TAG_NAME, "a"):
        href = get_attr_safe(a, "href")
        text = get_text_safe(a)
        if href and href.startswith("http") and href not in seen:
            seen.add(href)
            links.append({"text": text or href, "href": href})
    return links


def extract_attachments(driver):
    """Extract attachment file links (HackerOne stores them in S3)."""
    attachments = []
    seen = set()
    try:
        for a in driver.find_elements(By.CSS_SELECTOR, "a[href*='hackerone-us-west-2-production-attachments']"):
            href = get_attr_safe(a, "href")
            text = get_text_safe(a)
            if href and href not in seen:
                seen.add(href)
                attachments.append({"name": text or "attachment", "href": href})
    except Exception:
        pass
    return attachments


def extract_sidebar(driver):
    """Extract the right-side report metadata sidebar.
    Tries the specific class first, falls back to broader selectors.
    Returns raw text string of the sidebar.
    """
    selectors = [
        "[class*='spec-report-meta-sidebar']",
        "[class*='report-meta-sidebar']",
        "aside",
        "[class*='sidebar']",
    ]
    for sel in selectors:
        try:
            el = driver.find_element(By.CSS_SELECTOR, sel)
            text = el.text.strip()
            if text and len(text) > 10:
                print(f"[+] Found sidebar using selector: {sel}")
                return text
        except Exception:
            continue
    print("[!] Sidebar not found")
    return ""


def build_markdown(url, raw_text, sidebar_text, images, videos, links, attachments):
    """Build the final markdown document."""
    report_id = url.rstrip("/").split("/")[-1]
    lines = []

    lines.append(f"# HackerOne Report #{report_id}\n")
    lines.append(f"**Source:** {url}  \n")
    lines.append("---\n")

    # Sidebar metadata section (right panel)
    if sidebar_text:
        lines.append("## Report Metadata\n")
        lines.append(sidebar_text)
        lines.append("\n")

    lines.append("---\n")

    # Raw page text (the actual content)
    if raw_text:
        lines.append("## Report Content\n")
        lines.append(raw_text)
        lines.append("\n")

    # Images section
    if images:
        lines.append("\n---\n")
        lines.append("## Images\n")
        for i, img in enumerate(images, 1):
            alt = img.get("alt") or f"Image {i}"
            src = img["src"]
            lines.append(f"**{alt}**  ")
            lines.append(f"![{alt}]({src})\n")

    # Videos section
    if videos:
        lines.append("\n---\n")
        lines.append("## Videos / Screen Recordings\n")
        for i, src in enumerate(videos, 1):
            fname = src.split("/")[-1].split("?")[0] or f"video_{i}"
            lines.append(f"- **{fname}:** [{src}]({src})")

    # Attachments section
    if attachments:
        lines.append("\n---\n")
        lines.append("## Attachments\n")
        for att in attachments:
            lines.append(f"- [{att['name']}]({att['href']})")

    # Links section
    if links:
        lines.append("\n---\n")
        lines.append("## Links\n")
        for lnk in links:
            lines.append(f"- [{lnk['text']}]({lnk['href']})")

    return "\n".join(lines)


def scrape_report(url, output_dir=".", headless=True, retries=3):
    """Main scraping function with retry logic."""
    report_id = url.rstrip("/").split("/")[-1]
    output_file = os.path.join(output_dir, f"{report_id}.md")

    for attempt in range(1, retries + 1):
        print(f"\n[*] Attempt {attempt}/{retries}: {url}")
        driver = None
        try:
            driver = get_driver(headless=headless)

            # Set realistic UA via CDP if available
            try:
                driver.execute_cdp_cmd("Network.setUserAgentOverride", {
                    "userAgent": (
                        "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 "
                        f"(KHTML, like Gecko) Chrome/{CHROME_VERSION}.0.0.0 Safari/537.36"
                    )
                })
            except Exception:
                pass

            driver.get(url)
            wait_for_page_ready(driver, timeout=30)

            # Expand hidden content
            click_show_more(driver)
            time.sleep(1)

            # Scroll to load lazy content
            scroll_to_bottom(driver, pause=1.5)

            # Expand again after scroll
            click_show_more(driver)
            time.sleep(1)

            # Extract content
            print("[*] Extracting content...")
            content_div = find_content_wrapper(driver)
            raw_text = content_div.text.strip()

            if not raw_text or len(raw_text) < 100:
                print(f"[!] Content seems too short ({len(raw_text)} chars). Retrying...")
                if attempt < retries:
                    driver.quit()
                    time.sleep(3)
                    continue

            images = extract_images(content_div, driver)
            videos = extract_videos(content_div)
            links = extract_links(content_div)
            attachments = extract_attachments(driver)
            sidebar_text = extract_sidebar(driver)

            print(f"[+] Extracted {len(raw_text)} chars, {len(images)} images, {len(videos)} videos, {len(links)} links, sidebar={'yes' if sidebar_text else 'no'}")

            # Build and save markdown
            md = build_markdown(url, raw_text, sidebar_text, images, videos, links, attachments)
            os.makedirs(output_dir, exist_ok=True)
            with open(output_file, "w", encoding="utf-8") as f:
                f.write(md)

            print(f"[+] Saved: {output_file}")
            return output_file

        except TimeoutException as e:
            print(f"[!] Timeout on attempt {attempt}: {e}")
            if attempt < retries:
                print(f"[*] Waiting 5s before retry...")
                time.sleep(5)
        except WebDriverException as e:
            print(f"[!] WebDriver error on attempt {attempt}: {e}")
            if attempt < retries:
                time.sleep(5)
        except Exception as e:
            print(f"[!] Unexpected error on attempt {attempt}: {e}")
            import traceback
            traceback.print_exc()
            if attempt < retries:
                time.sleep(5)
        finally:
            if driver:
                try:
                    driver.quit()
                except Exception:
                    pass

    print(f"[!] All {retries} attempts failed.")
    sys.exit(1)


def main():
    parser = argparse.ArgumentParser(description="Scrape HackerOne reports to markdown")
    parser.add_argument("url", help="HackerOne report URL, e.g. https://hackerone.com/reports/1818163")
    parser.add_argument("-o", "--output", default=".", help="Output directory (default: current dir)")
    parser.add_argument("--no-headless", action="store_true", help="Run Chrome in visible mode (useful for debugging)")
    parser.add_argument("--retries", type=int, default=3, help="Number of retries on failure (default: 3)")
    args = parser.parse_args()

    if "hackerone.com/reports/" not in args.url:
        print("[!] Warning: URL doesn't look like a HackerOne report URL.")

    scrape_report(
        url=args.url,
        output_dir=args.output,
        headless=not args.no_headless,
        retries=args.retries,
    )


if __name__ == "__main__":
    main()
