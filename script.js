document.getElementById('categoryForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const categoryId = document.getElementById('categoryId').value;
    const categoryName = document.getElementById('categoryName').value;

    if (!/^C\d{3}$/.test(categoryId)) {
        alert('Category ID must be in the format C<CATEGORY_ID> (e.g., C001)');
        return;
    }

    const dateModified = new Date().toISOString().slice(0, 19).replace('T', ' ');

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'process.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status === 200) {
            loadCategories();
            document.getElementById('categoryForm').reset();
        }
    };
    xhr.send(`action=add&categoryId=${categoryId}&categoryName=${categoryName}&dateModified=${dateModified}`);
});

function loadCategories() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'process.php?action=load', true);
    xhr.onload = function () {
        if (this.status === 200) {
            const categories = JSON.parse(this.responseText);
            const tbody = document.querySelector('#categoryTable tbody');
            tbody.innerHTML = '';

            categories.forEach(category => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${category.category_id}</td>
                    <td>${category.category_Name}</td>
                    <td>${category.date_modified}</td>
                    <td>
                        <button onclick="deleteCategory('${category.category_id}')">Delete</button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }
    };
    xhr.send();
}

function deleteCategory(categoryId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'process.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status === 200) {
            loadCategories();
        }
    };
    xhr.send(`action=delete&categoryId=${categoryId}`);
}

window.onload = function () {
    loadCategories();
};
