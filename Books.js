import React, { useEffect, useState } from 'react';

// Determines the base URL for the backend API based on the environment.
// In production, this should be set to the actual deployed backend URL.
const API_BASE_URL = process.env.NODE_ENV === 'production'
  ? 'https://YOUR_DEPLOYED_BACKEND_URL/english-tutor-backend' // Replace with your actual deployed backend URL
  : 'http://localhost/english-tutor-backend';

function Books() {
  const [books, setBooks] = useState([]);
  const [message, setMessage] = useState('');

  // Stripe integration variables (uncomment and configure for live payments)
  // const stripe = window.Stripe('YOUR_STRIPE_PUBLISHABLE_KEY'); // Replace with actual Publishable Key
  // const [stripeInstance, setStripeInstance] = useState(null);

  useEffect(() => {
    // Initialize Stripe (uncomment for live payments)
    /*
    if (window.Stripe) {
      setStripeInstance(window.Stripe('YOUR_STRIPE_PUBLISHABLE_KEY'));
    } else {
      console.warn("Stripe.js not loaded. Retrying in 1 second...");
      const timer = setTimeout(() => {
        if (window.Stripe) {
          setStripeInstance(window.Stripe('YOUR_STRIPE_PUBLISHABLE_KEY'));
        } else {
          setMessage('Stripe payment functionality is unavailable. Please refresh the page.');
        }
      }, 1000);
      return () => clearTimeout(timer);
    }
    */

    // Fetch books from the backend API
    fetch(`${API_BASE_URL}/books.php`)
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          setMessage(`Error fetching books: ${data.error}`);
        } else {
          setBooks(data);
        }
      })
      .catch(error => {
        console.error('Error fetching books:', error);
        setMessage('An error occurred while fetching books. Ensure the backend server is running and accessible.');
      });
  }, []);

  const handleBuyNow = async (book) => {
    setMessage(`Simulating checkout for ${book.title}...`);

    // Mock Stripe checkout logic for testing purposes.
    // Replace this block with real Stripe integration for live payments.
    setTimeout(() => {
      const success = Math.random() > 0.1; // 90% chance of success

      if (success) {
        setMessage(`Success! You have "purchased" ${book.title}. (Mock Checkout)`);
        // In a live application, successful payment would trigger PDF delivery or access.
      } else {
        setMessage(`"Checkout" failed for ${book.title}. Please try again. (Mock Checkout)`);
      }
    }, 1500);

    // Real Stripe integration logic (uncomment and configure for live payments)
    /*
    if (!stripeInstance) {
      setMessage('Stripe is not ready. Please try again in a moment.');
      return;
    }

    setMessage(`Initiating real checkout for ${book.title}...`);
    try {
      const response = await fetch(`${API_BASE_URL}/create_checkout_session.php`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ bookId: book.id, bookTitle: book.title, bookPrice: book.price }),
      });

      const session = await response.json();

      if (session.id) {
        const result = await stripeInstance.redirectToCheckout({
          sessionId: session.id,
        });

        if (result.error) {
          setMessage(`Stripe checkout error: ${result.error.message}`);
        }
      } else {
        setMessage(`Error creating checkout session: ${session.error || 'Unknown error'}`);
      }
    } catch (error) {
      console.error('Error during Stripe checkout:', error);
      setMessage('An error occurred during checkout. Please try again.');
    }
    */
  };

  return (
    <div style={{ minHeight: '100vh', background: 'linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%)', padding: '4rem 0' }}>
      <h1 style={{
        textAlign: 'center',
        fontSize: '2.8rem',
        fontWeight: 800,
        color: '#2d3142',
        marginTop: '3.5rem',
        marginBottom: '2.5rem',
        letterSpacing: '2px'
      }}>Books for Sale</h1>
      {message && (
        <p style={{
          textAlign: 'center',
          fontSize: '1.1rem',
          color: message.includes('Error') || message.includes('failed') ? '#d9534f' : '#5cb85c',
          background: message.includes('Error') || message.includes('failed') ? '#f2dede' : '#dff0d8',
          border: `1px solid ${message.includes('Error') || message.includes('failed') ? '#ebccd1' : '#d6e9c6'}`,
          padding: '1rem',
          borderRadius: '8px',
          maxWidth: '800px',
          margin: '1.5rem auto'
        }}>
          {message}
        </p>
      )}
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(auto-fit, minmax(260px, 1fr))',
        gap: '2.5rem',
        maxWidth: '1200px',
        margin: '0 auto',
        padding: '0 2vw'
      }}>
        {books.map(book => (
          <div
            key={book.id}
            style={{
              background: '#fff',
              borderRadius: '1.2rem',
              boxShadow: '0 4px 24px rgba(44,62,80,0.10)',
              padding: '2rem 1.2rem 1.5rem 1.2rem',
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
              transition: 'transform 0.18s, box-shadow 0.18s',
              cursor: 'pointer',
              border: '1px solid #e0e7ef',
              position: 'relative',
              overflow: 'hidden',
            }}
            onMouseEnter={e => {
              e.currentTarget.style.transform = 'translateY(-6px) scale(1.03)';
              e.currentTarget.style.boxShadow = '0 8px 32px rgba(44,62,80,0.16)';
            }}
            onMouseLeave={e => {
              e.currentTarget.style.transform = 'none';
              e.currentTarget.style.boxShadow = '0 4px 24px rgba(44,62,80,0.10)';
            }}
          >
            <img
              src={`${API_BASE_URL}/${book.cover}`}
              alt="Cover"
              style={{
                width: '100%',
                maxWidth: '180px',
                height: '260px',
                objectFit: 'cover',
                borderRadius: '0.7rem',
                marginBottom: '1.2rem',
                boxShadow: '0 2px 12px rgba(44,62,80,0.10)'
              }}
            />
            <h2 style={{ fontSize: '1.3rem', fontWeight: 700, color: '#22223b', margin: '0 0 0.7rem 0', textAlign: 'center', letterSpacing: '1px' }}>{book.title}</h2>
            <p style={{ fontSize: '1.05rem', color: '#4f5d75', margin: '0 0 1.1rem 0', textAlign: 'center', minHeight: '48px' }}>{book.description}</p>
            <div style={{ fontWeight: 700, color: '#3a86ff', fontSize: '1.1rem', marginBottom: '1.2rem' }}>Price: ${book.price}</div>
            <div style={{ display: 'flex', gap: '1rem', marginTop: 'auto', justifyContent: 'center' }}>
              <button
                onClick={() => handleBuyNow(book)}
                style={{
                  background: 'linear-gradient(90deg, #3a86ff 0%, #4361ee 100%)',
                  color: '#fff',
                  border: 'none',
                  borderRadius: '0.6rem',
                  padding: '0.7rem 1.6rem',
                  fontSize: '1.08rem',
                  fontWeight: 600,
                  cursor: 'pointer',
                  boxShadow: '0 2px 8px rgba(44,62,80,0.08)',
                  transition: 'background 0.18s, box-shadow 0.18s',
                  opacity: 1
                }}
                onMouseEnter={e => {
                  e.currentTarget.style.background = 'linear-gradient(90deg, #4361ee 0%, #3a86ff 100%)';
                  e.currentTarget.style.boxShadow = '0 4px 16px rgba(44,62,80,0.13)';
                }}
                onMouseLeave={e => {
                  e.currentTarget.style.background = 'linear-gradient(90deg, #3a86ff 0%, #4361ee 100%)';
                  e.currentTarget.style.boxShadow = '0 2px 8px rgba(44,62,80,0.08)';
                }}
              >
                Buy Now
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default Books;
