tailwind.config = {
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Be Vietnam Pro"', 'sans-serif'], // Font cho nội dung
                serif: ['"Playfair Display"', 'serif'], // Font cho tiêu đề sang trọng
            },
            colors: {
                brand: {
                    50: '#f4f7f6',
                    100: '#e0f2fe',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    800: '#1f2937',
                    900: '#111827', // Màu chủ đạo (Xanh đen - Dark Blue)
                    gold: '#c5a47e', // Màu điểm nhấn (Vàng Gold - Luxury)
                    dark: '#1a1a1a',
                }
            }
        }
    }
}