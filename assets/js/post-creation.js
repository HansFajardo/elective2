document.getElementById('image').addEventListener('change', function () {
    var fileInput = this;
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if (!allowedExtensions.exec(filePath)) {
        alert('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
        fileInput.value = '';
        return false;
    }

    if (fileInput.files && fileInput.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('imagePreview').innerHTML = '<img src="' + e.target.result + '" style="max-width:200px; max-height:200px;">';
        };

        reader.readAsDataURL(fileInput.files[0]);
    }
});


setTimeout(closeSuccessMessage, 2000);

function closeSuccessMessage() {
    document.getElementById("successMessage").style.display = "none";
}