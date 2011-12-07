<!-- Include ONCE for ALL buttons in the page -->
<script type="text/javascript">
(function() {
    window.PinIt = window.PinIt || { loaded:false };
    if (window.PinIt.loaded) return;
    window.PinIt.loaded = true;
    function async_load(){
        var s = document.createElement("script");
        s.type = "text/javascript";
        s.async = true;
        s.src = "http://assets.pinterest.com/js/pinit.js";
        var x = document.getElementsByTagName("script")[0];
        x.parentNode.insertBefore(s, x);
    }
    if (window.attachEvent)
        window.attachEvent("onload", async_load);
    else
        window.addEventListener("load", async_load, false);
})();
</script>

<!-- Customize and include for EACH button in the page -->
<a href="http://pinterest.com/pin/create/button/" class="pin-it-button" count-layout="vertical">Pin It</a>




<img src="http://ww1.sinaimg.cn/bmiddle/5e421ec3jw1dk6afhiywrj.jpg"/>