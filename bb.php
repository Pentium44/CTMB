<?php
#    This file is part of xBoard2.
#    xBoard is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.
#
#    xBoard is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.

#    You should have received a copy of the GNU General Public License
#    along with xBoard2.  If not, see <http://www.gnu.org/licenses/>.
// based on http://www.phpit.net/article/create-bbcode-php/  
// modified by www.vision.to  
// please keep credits, thank you :-)  
// document your changes.  
function bbcode_format($str) {   
  
    $simple_search = array(   
                '/\[b\](.*?)\[\/b\]/is',  
                '/\[i\](.*?)\[\/i\]/is',  
                '/\[u\](.*?)\[\/u\]/is',  
                '/\[url\=(.*?)\](.*?)\[\/url\]/is',  
                '/\[url\](.*?)\[\/url\]/is',  
                '/\[align\=(left|center|right)\](.*?)\[\/align\]/is',  
                '/\[img\](.*?)\[\/img\]/is',  
                '/\[mail\=(.*?)\](.*?)\[\/mail\]/is',  
                '/\[mail\](.*?)\[\/mail\]/is',  
                '/\[font\=(.*?)\](.*?)\[\/font\]/is',   
                '/\[color\=(.*?)\](.*?)\[\/color\]/is',  
                 //added pre class for code presentation  
              '/\[code\](.*?)\[\/code\]/is',  
                //added paragraph  
              '/\[p\](.*?)\[\/p\]/is',  
                // Audio tag //
              '/\[audio\](.*?)\[\/audio\]/is',
                );  
  
    $simple_replace = array(  
                '<strong>$1</strong>',  
                '<em>$1</em>',  
                '<u>$1</u>',  
				// added nofollow to prevent spam  
                '<a href="$1" rel="nofollow" title="$2 - $1">$2</a>',  
                '<a href="$1" rel="nofollow" title="$1">$1</a>',  
                '<div style="text-align: $1;">$2</div>',  
				//added alt attribute for validation  
                '<a href="$1"><img style="margin: auto; width: 515px;" src="$1" alt="$1" /></a>',  
                '<a href="mailto:$1">$2</a>',  
                '<a href="mailto:$1">$1</a>',  
                '<span style="font-family: $1;">$2</span>',    
                '<span style="color: $1;">$2</span>',  
				//added pre class for code presentation  
				'<p><code class="code">$1</code></p>',  
				//added paragraph  
				'<p>$1</p>', 
				// Audio tag //
				'<object type="application/x-shockwave-flash" data="data/player.swf" width="200" height="20"><param name="movie" value="data/player.swf"><param name="bgcolor" value="#000000"><param name="FlashVars" value="mp3=$1&autoplay=1&buttoncolor=ffffff&slidercolor=ffffff&loadingcolor=989898"></object>',
                );  
  
    // Do simple BBCode's  
    $str = preg_replace ($simple_search, $simple_replace, $str);  
    //Spoilers.
	if(strstr($str, "[spoiler]") && !strstr($str, "[/spoiler]"))
		die("You didn't close the spoiler tag!\n");
	$str = str_replace("[spoiler]", '<span class="spoiler">', $str);
	$str = str_replace("[/spoiler]", '</span>', $str);

    // Do <blockquote> BBCode  
    $str = bbcode_quote ($str);  
  
    return $str;  
}  
function bbcode_quote ($str) {  
    //added div and class for quotes  
    $open = '<blockquote>';  
    $close = '</blockquote>';  
  
    // How often is the open tag?  
    preg_match_all ('/\[quote\]/i', $str, $matches);  
    $opentags = count($matches['0']);  
  
    // How often is the close tag?  
    preg_match_all ('/\[\/quote\]/i', $str, $matches);  
    $closetags = count($matches['0']);  
  
    // Check how many tags have been unclosed  
    // And add the unclosing tag at the end of the message  
    $unclosed = $opentags - $closetags;  
    for ($i = 0; $i < $unclosed; $i++) {  
        $str .= '</div></blockquote>';  
    }  
  
    // Do replacement  
    $str = str_replace ('[' . 'quote]', $open, $str);  
    $str = str_replace ('[/' . 'quote]', $close, $str);  
  
    return $str;  
}
?>