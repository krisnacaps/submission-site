document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk mengambil dan menampilkan tugas
    function fetchTasks() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'php/fetchTasks.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var tasks = JSON.parse(xhr.responseText);
                var tasksList = document.querySelector('ul');
                tasksList.innerHTML = '';

                tasks.forEach(function(task) {
                    var listItem = document.createElement('li');

                    if (task.username) { // Jika role adalah guru, tampilkan username siswa
                        listItem.innerHTML = task.username + ' - ';
                    }

                    var fileLink = document.createElement('a');
                    fileLink.href = 'php/download.php?id=' + task.id;
                    fileLink.textContent = task.filename;
                    listItem.appendChild(fileLink);

                    listItem.innerHTML += ' - ' + task.uploaded_at;

                    var deleteForm = document.createElement('form');
                    deleteForm.action = 'php/delete.php';
                    deleteForm.method = 'post';
                    deleteForm.style.display = 'inline';

                    var taskIdInput = document.createElement('input');
                    taskIdInput.type = 'hidden';
                    taskIdInput.name = 'task_id';
                    taskIdInput.value = task.id;
                    deleteForm.appendChild(taskIdInput);

                    var deleteButton = document.createElement('button');
                    deleteButton.type = 'submit';
                    deleteButton.textContent = 'Delete';
                    deleteForm.appendChild(deleteButton);

                    listItem.appendChild(deleteForm);
                    tasksList.appendChild(listItem);
                });
            } else {
                console.error('Failed to fetch tasks');
            }
        };
        xhr.send();
    }

    // Panggil fungsi fetchTasks untuk pertama kalinya
    fetchTasks();

    // Panggil fungsi fetchTasks setiap beberapa waktu tertentu (misalnya setiap 10 detik)
    setInterval(fetchTasks, 10000);
});
