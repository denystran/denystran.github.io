document.getElementById('apiForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const prompt = document.getElementById('prompt').value;

    // Gọi API backend
    fetch('http://localhost:3000/api/chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ prompt: prompt })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('output').textContent = data.choices[0].text; // Điều chỉnh theo cấu trúc phản hồi của API
    })
    .catch(error => {
        console.error('Lỗi khi gọi API:', error);
        document.getElementById('output').textContent = 'Đã xảy ra lỗi khi gọi API. Vui lòng thử lại.';
    });
});