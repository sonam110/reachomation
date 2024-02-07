const checkWebSite= () =>{
      var value = document.getElementById("searchInput").value;
      var check = value.search("https://www.");
      if(check == 0){
         document.getElementById("searchInput").value = value.replace("https://www.",'');
      }
   }

   $(document).ready(function(){
      setTimeout(function(){
         $('.alert').hide();
      }, 5000);
   });

   function showloader(){
        $('.loading').css('display','block');
   }
