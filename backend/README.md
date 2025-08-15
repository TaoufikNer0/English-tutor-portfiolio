# Book Selling Website - Simple Setup

A simple website to sell your digital books with secure payments.

## 🚀 Quick Start (2 Minutes)

### Step 1: Upload Files
1. **Upload all files** to your web server
2. **Visit:** `https://yourdomain.com/backend/setup.php`
3. **Click "Start Database Setup"**

### Step 2: Get Stripe Keys
1. **Go to:** [Stripe Dashboard](https://dashboard.stripe.com/apikeys)
2. **Copy your keys** (publishable and secret)
3. **Edit `backend/config.php`** and paste your keys

### Step 3: Change Admin Password
**Edit `backend/config.php`:**
```php
$admin_username = 'your_username';
$admin_password = 'your_password';
```

### Step 4: Update Website URL
**Edit `backend/config.php`:**
```php
$site_url = 'https://yourdomain.com';
```

## 📚 How It Works

### For You (Admin)
- **Login:** `https://yourdomain.com/backend/admin.php`
- **Upload books** with PDF and cover image
- **Set prices** for each book
- **View orders** and manage books

### For Customers
- **Visit:** `https://yourdomain.com/books.html`
- **See your books** with prices
- **Click "Buy Now"** → secure payment
- **Download PDF** after payment

## 🔧 Common Issues

**"Database connection failed"**
- Make sure your web server is running
- Check if MySQL is enabled

**"Upload failed"**
- Check file size (max 10MB)
- Make sure uploads folder exists

**"Payment not working"**
- Check your Stripe keys are correct
- Make sure you're using HTTPS

## 📞 Need Help?

1. **Check all settings** in `config.php`
2. **Look at server error logs**
3. **Contact your web hosting support**

## ✅ You're Done!

Your book selling website is ready! Start uploading books and making sales. 