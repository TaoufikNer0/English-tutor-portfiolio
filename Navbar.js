import React from 'react';
import { Link } from 'react-router-dom';
import { HashLink } from 'react-router-hash-link';

function Navbar() {
    return (
      <nav style={{
        background: "#555879",
        color: "#F4EBD3",
        padding: "2rem 8vw",
        position: "fixed",
        top: 0,
        left: 0,
        width: "100%",
        zIndex: 1000,
        boxShadow: "0 2px 16px rgba(0,0,0,0.1)",
        boxSizing: "border-box",
        display: "flex",
        alignItems: "center",
        justifyContent: "space-between"
      }}>
        <div style={{ display: "flex", alignItems: "center" }}>
          <Link to="/" style={{ textDecoration: 'none' }}>
            <span style={{
              fontWeight: 900,
              fontSize: "2.3rem",
              color: "#F4EBD3",
              letterSpacing: "4px",
              display: "flex",
              alignItems: "center",
              fontFamily: "'Montserrat', 'Poppins', 'Bebas Neue', Arial, sans-serif",
              textTransform: "uppercase"
            }}>
              <span role="img" aria-label="book" style={{ marginRight: "0.5rem", fontSize: "2.2rem" }}>📖</span>
              Anass
            </span>
          </Link>
        </div>
        <ul style={{ display: "flex", listStyle: "none", gap: "3rem", justifyContent: "flex-end", margin: 0 }}>
          <li><HashLink smooth to="/#home" style={{ color: "#F4EBD3", textDecoration: "none", fontSize: "1.4rem", fontWeight: "bold", letterSpacing: "1px" }}>Home</HashLink></li>
          <li><HashLink smooth to="/#about" style={{ color: "#F4EBD3", textDecoration: "none", fontSize: "1.4rem", fontWeight: "bold", letterSpacing: "1px" }}>About Me</HashLink></li>
          <li><Link to="/books" style={{ color: "#F4EBD3", textDecoration: "none", fontSize: "1.4rem", fontWeight: "bold", letterSpacing: "1px" }}>My Books</Link></li>
          <li><HashLink smooth to="/#services" style={{ color: "#F4EBD3", textDecoration: "none", fontSize: "1.4rem", fontWeight: "bold", letterSpacing: "1px" }}>Services</HashLink></li>
          <li><HashLink smooth to="/#contact" style={{ color: "#F4EBD3", textDecoration: "none", fontSize: "1.4rem", fontWeight: "bold", letterSpacing: "1px" }}>Contact</HashLink></li>
        </ul>
      </nav>
    );
}

export default Navbar;