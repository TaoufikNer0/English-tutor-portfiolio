import { FaLinkedin, FaInstagram, FaEnvelope } from "react-icons/fa";

function Contact() {
    return (
        <section
            id="contact"
            style={{
                minHeight: "100vh",
                background: "#DED3C4",
                color: "#555879",
                padding: "4rem 8vw",
                display: "flex",
                flexDirection: "column",
                alignItems: "center",
                justifyContent: "center"
            }}
        >
            <h2 style={{ fontSize: "2.5rem", marginBottom: "2rem", color: "#555879" }}>Contact Me</h2>
            <p style={{ fontSize: "1.3rem", marginBottom: "1rem", color: "#555879" }}>Phone: 02323232</p>
            <p style={{ fontSize: "1.3rem", marginBottom: "2rem", color: "#555879" }}>Email: your.email@example.com</p>
            <button style={{
                background: "#555879",
                color: "#F4EBD3",
                border: "none",
                padding: "1rem 2.5rem",
                borderRadius: "8px",
                fontSize: "1.2rem",
                cursor: "pointer",
                fontWeight: "bold",
                marginBottom: "2.5rem"
            }}>
                Send a Message
            </button>
            <div style={{ display: "flex", gap: "2.5rem", marginTop: "1.5rem" }}>
                <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" style={{ color: "#98A1BC", fontSize: "2.5rem" }} title="LinkedIn">
                    <FaLinkedin />
                </a>
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" style={{ color: "#98A1BC", fontSize: "2.5rem" }} title="Instagram">
                    <FaInstagram />
                </a>
                <a href="mailto:your.email@example.com" style={{ color: "#98A1BC", fontSize: "2.5rem" }} title="Email">
                    <FaEnvelope />
                </a>
            </div>
        </section>
    )
}
export default Contact;