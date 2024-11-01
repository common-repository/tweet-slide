(function() {
   tinymce.create('tinymce.plugins.tweetslide', {
      init : function(ed, url) {
         ed.addButton('tweetslide', {
            title : 'Twitter Slide',
            image : false,
            text : 'Tweet Slide',
            onclick : function() {
               ed.execCommand('mceInsertContent', false, '[tweetslide]');
            }
         });
      },
      createControl : function(n, cm) {
         return null;
      },
      getInfo : function() {
         return {
            longname : "Twitter Slide",
            author : 'ThemeWar',
            authorurl : 'http://themewar.com',
            infourl : 'http://themewar.com/plugins/tweetslide',
            version : "1.0"
         };
      }
   });
   tinymce.PluginManager.add('tweetslide', tinymce.plugins.tweetslide);
   
   
   
})();