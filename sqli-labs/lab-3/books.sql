-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2023 at 04:45 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blist`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `author` varchar(128) NOT NULL,
  `type` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `type`, `description`) VALUES
(1, 'Bug Bounty Bootcamp', 'Vickie Li', 'Hacking & BugBounty', 'Bug Bounty Bootcamp teaches you how to hack web applications. You will learn how to perform reconnaissance on a target, how to identify vulnerabilities, and how to exploit them. You’ll also learn how to navigate bug bounty programs set up by companies to reward security professionals for finding bugs in their web applications.'),
(2, 'Mastering Modern Web Penetration Testing', 'Prakhar Prasad ', 'Hacking & BugBounty', 'Master the art of conducting modern pen testing attacks and techniques on your web application before the hacker does! About This Book * This book covers the latest technologies such as Advance XSS, XSRF, SQL Injection, Web API testing, XML attack vectors, OAuth 2.0 Security, and more involved in today\'s web applications * Penetrate and secure your web application using various techniques * Get this comprehensive reference guide that provides advanced tricks and tools of the trade for seasoned penetration testers Who This Book Is For This book is for security professionals and penetration testers who want to speed up their modern web application penetrating testing. It will also benefit those at an intermediate level and web developers who need to be aware of the latest application hacking techniques. What You Will Learn * Get to know the new and less-publicized techniques such PHP Object Injection and XML-based vectors * Work with different security tools to automate most of the redundant tasks * See different kinds of newly-designed security headers and how they help to provide security * Exploit and detect different kinds of XSS vulnerabilities * Protect your web application using filtering mechanisms * Understand old school and classic web hacking in depth using SQL Injection, XSS, and CSRF * Grasp XML-related vulnerabilities and attack vectors such as XXE and DoS techniques * Get to know how to test REST APIs to discover security issues in them In Detail Web penetration testing is a growing, fast-moving, and absolutely critical field in information security. This book executes modern web application attacks and utilises cutting-edge hacking techniques with an enhanced knowledge of web application security. We will cover web hacking techniques so you can explore the attack vectors during penetration tests. The book encompasses the latest technologies such as OAuth 2.0, Web API testing methodologies and XML vectors used by hackers. Some lesser discussed attack vectors such as RPO (relative path overwrite), DOM clobbering, PHP Object Injection and etc. has been covered in this book. We\'ll explain various old school techniques in depth such as XSS, CSRF, SQL Injection through the ever-dependable SQLMap and reconnaissance. Websites nowadays provide APIs to allow integration with third party applications, thereby exposing a lot of attack surface, we cover testing of these APIs using real-life examples. This pragmatic guide will be a great benefit and will help you prepare fully secure applications. Style and approach This master-level guide covers various techniques serially. It is power-packed with real-world examples that focus more on the practical aspects of implementing the techniques rather going into detailed theory.'),
(3, 'The Web Application Hacker\'s Handbook', 'Dafydd Stuttard', 'Hacking & BugBounty', 'Web applications are the front door to most organizations, exposing them to attacks that may disclose personal information, execute fraudulent transactions, or compromise ordinary users. This practical book has been completely updated and revised to discuss the latest step-by-step techniques for attacking and defending the range of ever-evolving web applications. You\'ll explore the various new technologies employed in web applications that have appeared since the first edition and review the new attack techniques that have been developed, particularly in relation to the client side.\r\n\r\nReveals how to overcome the new technologies and techniques aimed at defending web applications against attacks that have appeared since the previous edition\r\nDiscusses new remoting frameworks, HTML5, cross-domain integration techniques, UI redress, framebusting, HTTP parameter pollution, hybrid file attacks, and more\r\nFeatures a companion web site hosted by the authors that allows readers to try out the attacks described, gives answers to the questions that are posed at the end of each chapter, and provides a summarized methodology and checklist of tasks\r\nFocusing on the areas of web application security where things have changed in recent years, this book is the most current resource on the critical topic of discovering, exploiting, and preventing web application security flaws.'),
(4, 'Hacking: The Art of Exploitation', 'Jon Erickson', 'Hacking & BugBounty', 'Hacking is the art of creative problem solving, whether that means finding an unconventional solution to a difficult problem or exploiting holes in sloppy programming. Many people call themselves hackers, but few have the strong technical foundation needed to really push the envelope.Rather than merely showing how to run existing exploits, author Jon Erickson explains how arcane hacking techniques actually work. To share the art and science of hacking in a way that is accessible to everyone, Hacking: The Art of Exploitation, 2nd Edition introduces the fundamentals of C programming from a hacker\'s perspective.The included LiveCD provides a complete Linux programming and debugging environment—all without modifying your current operating system. Use it to follow along with the book\'s examples as you fill gaps in your knowledge and explore hacking techniques on your own. Get your hands dirty debugging code, overflowing buffers, hijacking network communications, bypassing protections, exploiting cryptographic weaknesses, and perhaps even inventing new exploits. This book will teach you how to:– Program computers using C, assembly language, and shell scripts– Corrupt system memory to run arbitrary code using buffer overflows and format strings– Inspect processor registers and system memory with a debugger to gain a real understanding of what is happening– Outsmart common security measures like nonexecutable stacks and intrusion detection systems– Gain access to a remote server using port-binding or connect-back shellcode, and alter a server\'s logging behavior to hide your presence– Redirect network traffic, conceal open ports, and hijack TCP connections– Crack encrypted wireless traffic using the FMS attack, and speed up brute-force attacks using a password probability matrixHackers are always pushing the boundaries, investigating the unknown, and evolving their art. Even if you don\'t already know how to program, Hacking: The Art of Exploitation, 2nd Edition will give you a complete picture of programming, machine architecture, network communications, and existing hacking techniques. Combine this knowledge with the included Linux environment, and all you need is your own creativity.'),
(5, 'Real-World Bug Hunting', 'Peter Yaworski', 'Hacking & BugBounty', 'Learn how people break websites and how you can, too. Real-World Bug Hunting is the premier field guide to finding software bugs. Whether you\'re a cyber-security beginner who wants to make the internet safer or a seasoned developer who wants to write secure code, ethical hacker Peter Yaworski will show you how it\'s done. You\'ll learn about the most common types of bugs like cross-site scripting, insecure direct object references, and server-side request forgery. Using real-life case studies of rewarded vulnerabilities from applications like Twitter, Facebook, Google, and Uber, you\'ll see how hackers manage to invoke race conditions while transferring money, use URL parameter to cause users to like unintended tweets, and more.Each chapter introduces a vulnerability type accompanied by a series of actual reported bug bounties. The book\'s collection of tales from the field will teach you how attackers trick users into giving away their sensitive information and how sites may reveal their vulnerabilities to savvy users. You\'ll even learn how you could turn your challenging new hobby into a successful career. You\'ll learn:  •  How the internet works and basic web hacking concepts  •  How attackers compromise websites  •  How to identify functionality commonly associated with vulnerabilities  •  How to find bug bounty programs and submit effective vulnerability reportsReal-World Bug Hunting is a fascinating soup-to-nuts primer on web security vulnerabilities, filled with stories from the trenches and practical wisdom. With your new understanding of site security and weaknesses, you can help make the web a safer place--and profit while you\'re at it.'),
(6, 'Hacking Apis', 'Corey J. Ball', 'Hacking & BugBounty', 'Hacking APIs is a crash course on web API security testing that will prepare you to penetration-test APIs, reap high rewards on bug bounty programs, and make your own APIs more secure.\r\n \r\nYou’ll learn how REST and GraphQL APIs work in the wild and set up a streamlined API testing lab with Burp Suite and Postman. Then you’ll master tools useful for reconnaissance, endpoint analysis, and fuzzing, such as Kiterunner and OWASP Amass. Next, you’ll learn to perform common attacks, like those targeting an API’s authentication mechanisms and the injection vulnerabilities commonly found in web applications. You’ll also learn techniques for bypassing protections against these attacks.\r\n \r\nIn the book’s nine guided labs, which target intentionally vulnerable APIs, you’ll practice:\r\nEnumerating APIs users and endpoints using fuzzing techniques\r\nUsing Postman to discover an excessive data exposure vulnerability\r\nPerforming a JSON Web Token attack against an API authentication process\r\nCombining multiple API attack techniques to perform a NoSQL injection\r\nAttacking a GraphQL API to uncover a broken object level authorization vulnerability\r\n \r\nBy the end of the book, you’ll be prepared to uncover those high-payout API bugs other hackers aren’t finding and improve the security of applications on the web.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
