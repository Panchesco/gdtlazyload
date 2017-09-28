# Good at Lazy Load
Rewrite img tags to use a simple data-src technique for lazy loading of images.

See this [blog post](https://davidwalsh.name/lazyload-image-fade) by David Walsh for more information 
about how the rewritten HTML works with css and javascript to lazy load images.

## Tag Pairs

### Replace

Replaces the src attribute in img tags with data-src.

##### fallback

If you don't want the plugin to wrap images in a noscript tags as 
a fallback for browsers with javascript disabled, set this to 'n';


### Example

```
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Lazy Load</title>
	<style>
  	{exp:gdtlazyload:css}
  </style>
</head>
<body>
{exp:channel:entries
  channel="blog"
  status="open"}
 <article> 
 <h1>{title}</h1>
  {exp:gdtlazyload:replace fallback="y"}
    {blog_post}
  {/exp:gdtlazyload:replace}
</article>
{/exp:channel:entries}
<script>
{exp:gdtlazyload:js}
</script>
</body>
</html>
```

---

## Single Tags

### css

Outputs some starter CSS for displaying lazy load. 

```
img {
  opacity: 1;
  transition: opacity .3s;
  }
  
img[data-src] {
    opacity: 0;
  }
  
```
---

### js

Outputs some starter script code for loading image process with the ```{exp:gdtlazyload:replace}``` tag pair.

```
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
          
```
---




## Change Log
 - 1.0.0 Initial release
