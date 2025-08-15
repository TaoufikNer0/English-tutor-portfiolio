function Footer() {
  return (
    <footer style={{
      background: "#555879",
      color: "#F4EBD3",
      padding: "2rem 8vw",
      textAlign: "center",
      fontSize: "1.1rem"
    }}>
      <p>&copy; {new Date().getFullYear()} Creatd by: Taoufik Zayot. All rights reserved.</p>
      <div style={{ marginTop: "1rem" }}>
        <a href="https://linkedin.com" style={{ color: "#F4EBD3", margin: "0 1rem" }}>LinkedIn</a>
        <a href="https://instagram.com" style={{ color: "#F4EBD3", margin: "0 1rem" }}>Instagram</a>
      </div>
    </footer>
  );
}

export default Footer;
