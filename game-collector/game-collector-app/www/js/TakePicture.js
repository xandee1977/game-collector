var pictureSource;   // picture source
var destinationType; // sets the format of returned value

// Wait for device API libraries to load
//
document.addEventListener("deviceready",onDeviceReady,false);

// device APIs are available
//
function onDeviceReady() {
    pictureSource=navigator.camera.PictureSourceType;
    destinationType=navigator.camera.DestinationType;
}

// Called when a photo is successfully retrieved
//
function onPhotoDataSuccess(imageData) {
  var smallImage = document.getElementById('smallImage');
  //smallImage.style.display = 'block';
  smallImage.src = "data:image/jpeg;base64," + imageData;

  $.ajax({
    type: "POST",
    contentType: "application/json; charset=utf-8",
    url: "http://beecoapp.com/ws-game/upload_image.php",
    data: {"image": imageData},
    success: function(data) {
      console.log("Upload success!");
      console.log(data);
    },
    error: function(xhr, status) { console.log(xhr.responseText) }
  });

}

// Called when a photo is successfully retrieved
//
function onPhotoURISuccess(imageURI) {
  // Uncomment to view the image file URI
  // console.log(imageURI);

  // Get image handle
  //
  var largeImage = document.getElementById('largeImage');

  // Unhide image elements
  //
  largeImage.style.display = 'block';

  // Show the captured photo
  // The in-line CSS rules are used to resize the image
  //
  largeImage.src = imageURI;

  console.log(imageURI);
}

function capturePhoto() {
  // Take picture using device camera, allow edit, and retrieve image as base64-encoded string
  navigator.camera.getPicture(onPhotoDataSuccess, onFail, { quality: 20, allowEdit: true,
    destinationType: destinationType.DATA_URL });
}

function getPhoto(source) {
  // Retrieve image file location from specified source
  navigator.camera.getPicture(onPhotoURISuccess, onFail, { quality: 50,
    destinationType: destinationType.FILE_URI,
    sourceType: source });
}

function onFail(message) {
  alert('Failed because: ' + message);
}