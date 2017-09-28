<?php
/**
 * Gdtlazyload Class
 *
 * @package ExpressionEngine
 * @author Richard Whitmer
 * @copyright (c) 2017
 * @license MIT
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @link http://github.com/Panchesco
 * @since Version 3.5.11
 */

// ------------------------------------------------------------------------

/**
 * Good at Strings Plugin
 *
 * @package     ExpressionEngine
 * @subpackage  user/addons
 * @category    Plugin
 * @author      Richard Whitmer
 * @copyright   Copyright (c) 2017
 * @link        http://github.com/Panchesco/gdtlazyload.git
 */

// ------------------------------------------------------------------------

class Gdtlazyload
{
	public

	function __construct()
		{
		}

	// ------------------------------------------------------------------------

	/**
	 * Outputs a script for lazyload images processed with the replace method.
	 *
	 */
	public function js()
		{
		return "
          function insertImg (noscript) {   
              var img = new Image();
              img.setAttribute('data-src', '');
              noscript.parentNode.insertBefore(img, noscript);
              img.onload = function() {
              img.removeAttribute('data-src');
            }
            img.src = noscript.getAttribute('data-src');
          }

          [].forEach.call(document.querySelectorAll('noscript'), function(noscript) {
          insertImg(noscript);
           
          });
          ";
		}

	// ------------------------------------------------------------------------

	/**
	 * Outputs minimum CSS for lazyload.
	 *
	 */
	public function css()
		{
		return "
            img {
              opacity: 1;
              transition: opacity .3s;
              }
              img[data-src] {
                opacity: 0;
              }
          ";
		}

	// ------------------------------------------------------------------------

	/**
	 * Replaces src attribute in img tags with data-src.
	 * @return string
	 */
	public function replace()
		{
		$tagdata = ee()->TMPL->tagdata;
		$fallback = ee()->TMPL->fetch_param('fallback', 'y');
		$fallback = str_ireplace(array(
			'y',
			'yes',
			'true',
			1
		) , 'y', $fallback);
		$string = '';

		// Isolate image tags on single lines.

		$tagdata = str_replace("<img", "\n<img", $tagdata);
		$tagdata = str_replace(">", ">\n", $tagdata);
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $tagdata) as $key => $line)
			{
			$line = trim($line);

			// Standardize quotation marks.

			$line = str_replace("'", '"', $line);

			// If the line contains and image tag, get the img src and wrap it in a noscript tag.

			if (strpos($line, '<img') !== FALSE)
				{
				$pos = strpos($line, 'src=');
				$src = substr($line, $pos);
				$src = str_replace('src="', '', $src);
				$src = substr($src, 0, strpos($src, '"'));

				// Now that we have the src, create the noscript wrapper with data-src info.

				if ($fallback == 'y')
					{
					$line = '<noscript data-src="' . $src . '">' . str_replace('src=', 'data-src="" src=', $line) . '</noscript>';
					}
				  else
					{
					$line = str_replace('src=', 'data-src="" src=', $line);
					}
				}

			if (!empty($line))
				{
				$string.= $line . "\n";
				}
			}

		return $string;
		}
	}

/* End of file pi.gdtlazyload.php */
/* Location: ./system/user/addons/gdtlazyload/pi.gdtlazyload.php */
