let index = 0;
const imageWidth = 1200;
const marginRight = 10;
const autoSlideInterval = 5000;

function moveSlide(direction) {
    const slides = document.querySelectorAll('.slider-content-top a');
    const totalSlides = slides.length;

    index = (index + direction + totalSlides) % totalSlides;

    const offset = -index * (imageWidth + marginRight);
    document.querySelector('.slider-content-top').style.transform = `translateX(${offset}px)`;
}

setInterval(() => {
    moveSlide(1);
}, autoSlideInterval);

const productList = document.getElementById('product-list');
const nextBtn = document.getElementById('next-btn');
const prevBtn = document.getElementById('prev-btn');

const itemsToShow = 5; 

const products = Array.from(productList.children); 

let currentIndex = 0;

function updateProductDisplay() {
    products.forEach((product, index) => {
        if (index < currentIndex || index >= currentIndex + itemsToShow) {
            product.style.display = 'none';
        } else {
            product.style.display = 'block';
        }
    });

    if (currentIndex === 0) {
        prevBtn.style.opacity = 0.2;
        prevBtn.disabled = true;
    } else {
        prevBtn.style.opacity = 1;
        prevBtn.disabled = false;
    }

    if (currentIndex + itemsToShow >= products.length) {
        nextBtn.style.opacity = 0.2;
        nextBtn.disabled = true;
    } else {
        nextBtn.style.opacity = 1;
        nextBtn.disabled = false;
    }
}

updateProductDisplay(); 

// Thêm sự kiện cho nút "tiếp theo"
nextBtn.addEventListener('click', () => {
    if (currentIndex + itemsToShow < products.length) {
        currentIndex++;
    } 
    updateProductDisplay();
});

// Thêm sự kiện cho nút "quay lại"
prevBtn.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
    } 
    updateProductDisplay();
});


//---------------------product news----------------
const newsItems = document.querySelectorAll('.product-news-content-product-item');
const prev_Btn = document.querySelector('#prevbtn');
const next_Btn = document.querySelector('#nextbtn');

const itemsPerPage = 4;

let newsCurrentIndex = 0;

function updateSlider() {
    newsItems.forEach((item) => {
        item.style.display = 'none';
    });

    for (let i = newsCurrentIndex; i < newsCurrentIndex + itemsPerPage; i++) {
        if (newsItems[i]) {
            newsItems[i].style.display = 'flex';
        }
    }

    if (newsCurrentIndex === 0) {
        prev_Btn.style.opacity = 0.3;
        prev_Btn.disabled = true;
    } else {
        prev_Btn.style.opacity = 1;
        prev_Btn.disabled = false;
    }

    if (newsCurrentIndex + itemsPerPage >= newsItems.length) {
        next_Btn.style.opacity = 0.3;
        next_Btn.disabled = true;
    } else {
        next_Btn.style.opacity = 1;
        next_Btn.disabled = false;
    }
}

// Sự kiện click cho nút "tiếp theo"
next_Btn.addEventListener('click', () => {
    if (newsCurrentIndex + itemsPerPage < newsItems.length) {
        newsCurrentIndex++;
    }
    updateSlider();
});

// Sự kiện click cho nút "quay lại"
prev_Btn.addEventListener('click', () => {
    if (newsCurrentIndex > 0) {
        newsCurrentIndex--;
    }
    updateSlider();
});

updateSlider();
