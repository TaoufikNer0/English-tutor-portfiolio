function Services() {
  return (
    <section
      id="services"
      style={{
        minHeight: "100vh",
        background: "#F4EBD3",
        color: "#555879",
        padding: "4rem 8vw"
      }}
    >
      <h2 style={{ fontSize: "2.5rem", marginBottom: "2.5rem", textAlign: "center", color: "#555879" }}>What Can I Do</h2>
      <div style={{
        display: "flex",
        gap: "2.5rem",
        flexWrap: "wrap",
        justifyContent: "center"
      }}>
        {[
          { title: "Creative Lessons", desc: "Fun, engaging lessons tailored to your needs." },
          { title: "Exam Prep", desc: "Get ready for IELTS, TOEFL, and more." },
          { title: "Business English", desc: "Improve your English for the workplace." },
          { title: "Pronunciation Coaching", desc: "Master English sounds and speak clearly and confidently." },
          { title: "Writing Skills", desc: "Develop your writing for essays, emails, and more." },
          { title: "Conversation Practice", desc: "Boost your fluency with real-life speaking practice." },
          { title: "Accent Reduction", desc: "Learn techniques to sound more like a native speaker." }
        ].map((service, i) => (
          <div key={i} style={{
            background: "#DED3C4",
            padding: "2rem",
            borderRadius: "16px",
            minWidth: "260px",
            maxWidth: "320px",
            flex: "1 1 300px",
            boxShadow: "0 4px 16px rgba(85, 88, 121, 0.08)",
            color: "#555879"
          }}>
            <h3 style={{ fontSize: "1.5rem", marginBottom: "1rem", color: "#555879" }}>{service.title}</h3>
            <p style={{ fontSize: "1.1rem" }}>{service.desc}</p>
          </div>
        ))}
      </div>
    </section>
  );
}

export default Services;
