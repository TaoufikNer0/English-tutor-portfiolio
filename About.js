import secondPortrait from './assets/images/avatar.png';

function About() {
    return (
        <section
            id="about"
            style={{
                minHeight: "100vh",
                display: "flex",
                alignItems: "center",
                justifyContent: "space-between",
                background: "#DED3C4",
                color: "#555879",
                padding: "4rem 8vw"
            }}
        >
            <img
                src={secondPortrait}
                alt="Anass teaching"
                style={{
                    width: "520px",
                    height: "700px",
                    objectFit: "cover",
                    boxShadow: "0 12px 48px rgba(0,0,0,0.1)",
                    marginRight: "3rem",
                    filter: "grayscale(100%)"
                }}
            />
            <div style={{ maxWidth: "800px", marginLeft: "3rem" }}>
                <h2 style={{ fontSize: "2.8rem", marginBottom: "1.5rem", fontWeight: 900, letterSpacing: "2px", color: "#555879", textTransform: "uppercase" }}>About Me</h2>
                <p style={{ fontSize: "1.5rem", marginBottom: "2rem", lineHeight: 1.6, fontWeight: 500, color: "#555879" }}>
                    Hi! I'm Anass, a passionate English tutor dedicated to helping students achieve their language goals. I specialize in conversational English, exam preparation, and grammar coaching. My approach is friendly, creative, and tailored to each student's needs.
                </p>
                <div style={{ marginBottom: "2rem" }}>
                    <span style={{
                        background: "#555879",
                        color: "#F4EBD3",
                        padding: "0.6rem 1.4rem",
                        borderRadius: "8px",
                        marginRight: "1rem",
                        fontSize: "1.2rem",
                        fontWeight: "bold",
                        letterSpacing: "1px"
                    }}>IELTS</span>
                    <span style={{
                        background: "#555879",
                        color: "#F4EBD3",
                        padding: "0.6rem 1.4rem",
                        borderRadius: "8px",
                        marginRight: "1rem",
                        fontSize: "1.2rem",
                        fontWeight: "bold",
                        letterSpacing: "1px"
                    }}>Grammar</span>
                    <span style={{
                        background: "#555879",
                        color: "#F4EBD3",
                        padding: "0.6rem 1.4rem",
                        borderRadius: "8px",
                        fontSize: "1.2rem",
                        fontWeight: "bold",
                        letterSpacing: "1px"
                    }}>Conversation</span>
                </div>
                <button style={{
                    background: "#555879",
                    color: "#F4EBD3",
                    border: "none",
                    padding: "1rem 2.5rem",
                    borderRadius: "10px",
                    fontSize: "1.2rem",
                    fontWeight: "bold",
                    cursor: "pointer",
                    letterSpacing: "1px"
                }}>
                    Download CV
                </button>
            </div>
        </section>
    );
}

export default About;