function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('profile_picture');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}

document.getElementById('profile_pic').addEventListener('change', previewImage);
