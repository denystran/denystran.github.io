const express = require('express');
const axios = require('axios');
const cors = require('cors');
require('dotenv').config();

const app = express();
const port = 3000;

// Middleware
app.use(cors());
app.use(express.json());

// Endpoint để xử lý yêu cầu từ frontend
app.post('/api/chat', async (req, res) => {
    try {
        const { prompt } = req.body;

        // Gọi API DeepSeek-V3
        const response = await axios.post(
            'https://api.deepseek.com/v1/endpoint', // Thay bằng endpoint thực tế
            {
                prompt: prompt,
                max_tokens: 100
            },
            {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${process.env.API_KEY}`
                }
            }
        );

        // Trả về kết quả cho frontend
        res.json(response.data);
    } catch (error) {
        console.error('Lỗi khi gọi API:', error);
        res.status(500).json({ error: 'Đã xảy ra lỗi khi gọi API' });
    }
});

// Khởi động server
app.listen(port, () => {
    console.log(`Server đang chạy tại http://localhost:${port}`);
});