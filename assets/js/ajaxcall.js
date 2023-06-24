$(document).ready(function() {

  /**
   * Function to load music on page load and add pagination to load more music.
   * 
   *  @param int page
   *    Holds page number to be display.
   */
  function loadMusic(page) {
    $.ajax({
      url: "./music.php",
      type: "POST",
      data: {page_no :page},
      success: function(data) {
        $("#musiclist").html(data);
      }
    });
  }

  loadMusic();

  // It will page number to be loaded.
  $(document).on("click", "#pagination a", function(e) {
    e.preventDefault();
    var page_id = $(this).attr("id");

    loadMusic(page_id);
  });

  /**
   * This function will add music to user favourite list or remove music from
   * favourite list.
   */
  function favourite() {
    $.ajax({
      url: "./addfavourite.php",
      type: "POST",
      success: function(data) {
        var favbutton;
        if (data == 1) {
          favbutton = '<a href="" id="fav-btn"><i class="fa fa-heart fa_custom fa-2x liked"></i></a>';
        }
        else {
          favbutton = '<a href="" id="fav-btn"><i class="fa fa-heart fa_custom fa-2x unliked"></i></a>';
        }
        $("#fav").html(favbutton);
      }
    });
  }

  // Favourite button to add or remove favourite music.
  $(document).on("click", "#fav-btn", function(e) {
    e.preventDefault();
    favourite();
  });

  /**
   * Function to load user uploaded music files from database with pagination.
   * 
   *  @param int page
   *    Holds page number to be shown.
   */
  function loadUserUpload(page) {
    $.ajax({
      url: "./uploads.php",
      type: "POST",
      data: {page_no: page},
      success: function(response) {
        $("#uploads").html(response);
      }
    });
  }

  loadUserUpload();

  // It will show page number to be loaded.
  $(document).on("click", "#user-uploads a", function(e) {
    e.preventDefault();
    var page_id = $(this).attr("id");

    loadUserUpload(page_id);
  });

  /**
   * Function to load user favourites songs.
   * 
   *  @param int page
   *    Holds page number to be shown. 
   */
  function loadUserFavourite(page) {
    $.ajax({
      url: "favourites.php",
      type: "POST",
      data: {page_no: page},
      success: function(response) {
        $("#favourite").html(response);
      }
    });
  }

  loadUserFavourite();

  // It will page  number to be loaded.
  $(document).on("click", "#user-fav a", function(e) {
    e.preventDefault();
    var page_id = $(this).attr("id");

    loadUserFavourite(page_id);
  })
});
