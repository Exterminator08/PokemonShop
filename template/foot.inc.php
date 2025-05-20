</main>

<script src="js/uikit.min.js"></script>
<script src="js/uikit-icons.min.js"></script>
<script src="js/main.js"></script>   

<script> // save theme
   function setTheme(themeName) {
      localStorage.setItem('selectedTheme', themeName);
      document.getElementById('theme-style').href = 'css/' + themeName + '.css';
   }

   (function() {
      var savedTheme = localStorage.getItem('selectedTheme') || 'style';
      document.getElementById('theme-style').href = 'css/' + savedTheme + '.css';
   })();
</script>
</body>
</html>
