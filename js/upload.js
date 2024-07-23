// js/upload.js
document.getElementById('uploadForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var formData = new FormData(this);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/upload.php', true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('uploadStatus').innerHTML = 'File uploaded successfully!';
        } else {
            document.getElementById('uploadStatus').innerHTML = 'Error uploading file.';
        }
    };

    xhr.send(formData);
});
