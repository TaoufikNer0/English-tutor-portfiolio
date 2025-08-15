import anassPortrait from './assets/images/avatar.png';

function Hero() {
  return (
    <section
      id="home"
      style={{
        height: "100vh",
        display: "flex",
        alignItems: "center",
        justifyContent: "space-between",
        background: "#F4EBD3",
        color: "#555879",
        padding: "0 8vw",
        boxSizing: "border-box"
      }}
    >
      <div style={{
        maxWidth: "600px",
        display: "flex",
        flexDirection: "column",
        justifyContent: "center",
        height: "100%"
      }}>
        <h1 style={{ fontSize: "3.5rem", margin: "0 0 1rem 0", textAlign: "center" }}>Hello, My Name Is</h1>
        <h2 style={{ color: "#555879", fontSize: "3rem", margin: "0 0 1.5rem 0", textAlign: "center" }}>Anass</h2>
        <p style={{ fontSize: "1.5rem", marginBottom: "2.5rem", textAlign: "center", color: "#555879" }}>
          A passionate English tutor helping students achieve their language goals.
        </p>
        <div style={{ display: "flex", justifyContent: "center" }}>
          <button style={{
            background: "#555879",
            color: "#F4EBD3",
            border: "none",
            padding: "1.2rem 3rem",
            borderRadius: "10px",
            fontSize: "1.3rem",
            cursor: "pointer",
            fontWeight: "bold"
          }}>
            Contact me
          </button>
        </div>
      </div>
      <img
        src={anassPortrait}
        alt="Anass portrait"
        style={{
          borderRadius: "24px",
          width: "600px",
          height: "calc(100vh - 100px)",
          objectFit: "cover",
          filter: "grayscale(100%)",
          alignSelf: "flex-end"
        }}
      />
    </section>
  );
}

export default Hero;