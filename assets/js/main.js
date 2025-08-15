// JavaScript for Anass English Tutor Portfolio with Backend Integration
// This file handles dynamic functionality and Stripe payments

// Configuration
const API_BASE_URL = 'http://localhost/000portfolio/backend';

// Global variables
let books = [];
let stripe = null;

// Initialize Stripe
function initializeStripe() {
    if (typeof Stripe !== 'undefined') {
        stripe = Stripe('pk_test_your_publishable_key_here'); // Update with your Stripe key
    }
}

// Load books from backend
function loadBooks() {
    const loadingElement = document.getElementById('loading');
    const booksGrid = document.getElementById('booksGrid');
    const messageElement = document.getElementById('message');
    
    if (loadingElement) loadingElement.classList.remove('d-none');
    if (messageElement) messageElement.classList.add('d-none');
    if (booksGrid) booksGrid.innerHTML = '';
    
    fetch(`${API_BASE_URL}/books.php`)
        .then(response => response.json())
        .then(data => {
            if (loadingElement) loadingElement.classList.add('d-none');
            
            if (data.error) {
                showMessage(`Error: ${data.error}`, 'danger');
                return;
            }
            
            books = data;
            displayBooks(data);
        })
        .catch(error => {
            console.error('Error:', error);
            if (loadingElement) loadingElement.classList.add('d-none');
            showMessage('Failed to load books. Check if backend is running.', 'danger');
        });
}

// Display books in grid
function displayBooks(booksData) {
    const booksGrid = document.getElementById('booksGrid');
    if (!booksGrid) return;
    
    if (!booksData || booksData.length === 0) {
        booksGrid.innerHTML = '<div class="col-12 text-center"><p class="lead">No books available.</p></div>';
        return;
    }
    
    const booksHTML = booksData.map(book => `
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card h-100 book-card" style="border-radius:1.2rem;box-shadow:0 4px 24px rgba(44,62,80,0.10);border:1px solid #e0e7ef;transition:transform 0.18s, box-shadow 0.18s;cursor:pointer;">
                <img src="${API_BASE_URL}/${book.cover}" alt="${book.title} Cover" class="card-img-top" style="height:260px;object-fit:cover;border-radius:0.7rem 0.7rem 0 0;box-shadow:0 2px 12px rgba(44,62,80,0.10);">
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title fw-bold" style="color:#22223b;font-size:1.3rem;letter-spacing:1px;">${book.title}</h3>
                    <p class="card-text flex-grow-1" style="color:#4f5d75;font-size:1.05rem;min-height:48px;">${book.description}</p>
                    <div class="fw-bold text-primary mb-3" style="font-size:1.1rem;">Price: $${book.price}</div>
                    <button onclick="handleBuyNow(${book.id})" class="btn btn-primary w-100" style="background:linear-gradient(90deg, #3a86ff 0%, #4361ee 100%);border:none;border-radius:0.6rem;font-weight:600;box-shadow:0 2px 8px rgba(44,62,80,0.08);">
                        Buy Now
                    </button>
                </div>
            </div>
        </div>
    `).join('');
    
    booksGrid.innerHTML = booksHTML;
    addBookCardHoverEffects();
}

// Add hover effects to book cards
function addBookCardHoverEffects() {
    document.querySelectorAll('.book-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-6px) scale(1.03)';
            this.style.boxShadow = '0 8px 32px rgba(44,62,80,0.16)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'none';
            this.style.boxShadow = '0 4px 24px rgba(44,62,80,0.10)';
        });
    });
}

// Handle Buy Now button click
async function handleBuyNow(bookId) {
    const book = books.find(b => b.id === bookId);
    if (!book) {
        showMessage('Book not found!', 'danger');
        return;
    }
    
    // Get customer email
    const customerEmail = prompt('Please enter your email address:');
    if (!customerEmail) return;
    
    showMessage('Processing payment...', 'info');
    
    try {
        const response = await fetch(`${API_BASE_URL}/create_checkout_session.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                bookId: book.id,
                bookTitle: book.title,
                bookPrice: book.price,
                customerEmail: customerEmail
            })
        });
        
        const session = await response.json();
        
        if (session.error) {
            showMessage(`Payment error: ${session.error}`, 'danger');
            return;
        }
        
        // Redirect to Stripe checkout
        if (stripe) {
            const result = await stripe.redirectToCheckout({ sessionId: session.id });
            if (result.error) {
                showMessage(`Stripe error: ${result.error.message}`, 'danger');
            }
        } else {
            showMessage('Stripe not loaded. Please refresh the page.', 'danger');
        }
        
    } catch (error) {
        console.error('Payment error:', error);
        showMessage('Payment processing failed. Please try again.', 'danger');
    }
}

// Show message to user
function showMessage(message, type = 'info') {
    const messageElement = document.getElementById('message');
    if (!messageElement) return;
    
    messageElement.className = `alert alert-${type}`;
    messageElement.textContent = message;
    messageElement.classList.remove('d-none');
    
    if (type === 'success') {
        setTimeout(() => messageElement.classList.add('d-none'), 5000);
    }
}

// Smooth scrolling for navigation
function setupSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Set current year in footer
    const yearElement = document.getElementById('year');
    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }
    
    // Initialize Stripe
    initializeStripe();
    
    // Setup smooth scrolling
    setupSmoothScrolling();
    
    // Load books if on books page
    if (document.getElementById('booksGrid')) {
        loadBooks();
    }
    
    // Add button hover effects
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'none';
            this.style.boxShadow = 'none';
        });
    });
}); 