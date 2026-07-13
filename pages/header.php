<style>
  /* ---------- Nav ---------- */
  header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 50;
    padding: 10px 0;
    transition:
      background 0.35s ease,
      padding 0.35s ease;
  }
  header.scrolled {
    background: rgba(18, 42, 31, 0.94);
    padding: 5px 0;
    backdrop-filter: blur(8px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
  }
  header.scrolled .brand img {
    background-color: white;
    padding: 8px;
  }
  nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
  }
  .nav-shell {
    display: flex;
    align-items: center;
    gap: 18px;
  }
  .brand {
    font-family: var(--font-display);
    font-size: 1.3rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    color: var(--cream);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .brand .logo-full {
    display: block;
    height: 90px;
    width: auto;
    transition: all 0.35s ease;
  }
  .brand .logo-icon {
    display: none;
    height: 60px;
    width: auto;
    transition: all 0.35s ease;
  }
  header.scrolled .brand .logo-full {
    height: 60px;
  }
  .nav-links {
    display: flex;
    gap: 32px;
    list-style: none;
  }
  .nav-links a {
    position: relative;
    color: var(--cream);
    text-decoration: none;
    font-size: 0.92rem;
    letter-spacing: 0.03em;
    font-weight: 500;
    opacity: 0.88;
    transition: opacity 0.2s;
  }
  .nav-links a::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -6px;
    width: 0;
    height: 1px;
    background: var(--gold);
    transition: width 0.25s ease;
  }
  .nav-links a:hover {
    opacity: 1;
  }
  .nav-links a:hover::after {
    width: 100%;
  }
  .nav-toggle {
    display: none;
    position: relative;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
  }
  .nav-toggle span {
    position: absolute;
    left: 50%;
    display: block;
    width: 18px;
    height: 2px;
    background: var(--cream);
    transform: translateX(-50%);
    transition:
      top 0.25s ease,
      transform 0.25s ease,
      opacity 0.2s ease;
  }
  .nav-close {
    display: none;
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1020;
    width: 42px;
    height: 42px;
    border: 1px solid rgba(246, 242, 233, 0.24);
    background: rgba(246, 242, 233, 0.08);
    color: var(--cream);
    font-size: 2rem;
    line-height: 1;
    cursor: pointer;
  }
  .nav-close.open {
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  /* ---------- Responsive for Nav ---------- */
  @media (max-width: 767px) {
    header {
      padding: 12px 0;
    }
    header.scrolled {
      padding: 8px 0;
    }
    .nav-shell {
      background: rgba(18, 42, 31, 0.94);
      padding: 10px 14px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
      backdrop-filter: blur(10px);
      border-radius: 0;
    }
    .brand .logo-full {
      display: none;
    }
    .brand .logo-icon {
      display: block;
      height: 50px;
    }
    header.scrolled .brand .logo-icon {
      height: 40px;
      padding: 4px;
    }
    .nav-links {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 100vw;
      background: var(--forest-deep);
      flex-direction: column;
      padding: 96px 24px 32px;
      transform: translateX(100%);
      transition: transform 0.3s ease;
      gap: 26px;
      z-index: 1010;
      display: flex;
    }
    .nav-links.open {
      transform: translateX(0);
    }
    .nav-toggle {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      background: rgba(246,242,233,0.08);
      margin-left: 6px;
    }
    .nav-close.open {
      display: inline-flex;
    }
  }
</style>

<header id="siteHeader">
  <nav class="wrap">
    <div class="nav-shell">
      <a href="<?php echo htmlspecialchars($baseLink('')); ?>" class="brand">
        <img src="<?php echo htmlspecialchars($baseLink('img/logo.png')); ?>" alt="Virunga Collective Logo" class="logo-full">
        <img src="<?php echo htmlspecialchars($baseLink('img/icon.png')); ?>" alt="Virunga Collective Icon" class="logo-icon">
      </a>
      <button
        class="nav-toggle"
        id="navToggle"
        aria-label="Open menu"
        aria-controls="navLinks"
        aria-expanded="false"
      >
        <span style="top: 12px;"></span><span style="top: 18px;"></span><span style="top: 24px;"></span>
      </button>
    </div>
    <ul class="nav-links" id="navLinks">
      <li><a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>">Journeys</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('homestays')); ?>">Stays</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('ecotours/community')); ?>">Community Impact</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('about-us')); ?>">Story</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('contact-us')); ?>">Enquire</a></li>
    </ul>
    <button
      class="nav-close"
      id="navClose"
      aria-label="Close menu"
      aria-controls="navLinks"
    >
      &times;
    </button>
  </nav>
</header>

<script>
  // Header scroll effect
  const header = document.getElementById('siteHeader');
  if (header) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });
  }

  // Mobile nav toggle
  const navToggle = document.getElementById('navToggle');
  const navClose = document.getElementById('navClose');
  const navLinks = document.getElementById('navLinks');

  if (navToggle && navClose && navLinks) {
    function setMenuState(isOpen) {
      navLinks.classList.toggle('open', isOpen);
      navClose.classList.toggle('open', isOpen);
      navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }

    navToggle.addEventListener('click', () => {
      setMenuState(!navLinks.classList.contains('open'));
    });
    navClose.addEventListener('click', () => {
      setMenuState(false);
    });
    navLinks.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => {
        setMenuState(false);
      });
    });
  }
</script>