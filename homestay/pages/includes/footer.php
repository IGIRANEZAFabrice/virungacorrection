    <section class="imigongo-strip" aria-hidden="true"></section>

<footer class="footer">
      <!-- Imigongo: top-right -->
      <svg
        class="imigongo imigongo--tl"
        viewBox="0 0 280 280"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
      >
        <defs>
          <style>
            .ig {
              fill: var(--color-primary);
            }
            .ig2 {
              fill: var(--color-primary-dark);
            }
            .ig3 {
              fill: var(--color-primary-light);
            }
          </style>
        </defs>
        <!-- Imigongo: bold angular spiral/geometric blocks -->
        <!-- Outer triangles -->
        <polygon class="ig" points="0,0 280,0 280,280" />
        <polygon class="ig2" points="0,0 260,0 0,260" />
        <!-- Diagonal checker bands -->
        <polygon class="ig3" points="280,0 280,60 220,0" />
        <polygon class="ig" points="280,60 280,120 160,0 220,0" />
        <polygon class="ig2" points="280,120 280,180 100,0 160,0" />
        <polygon class="ig3" points="280,180 280,240 40,0 100,0" />
        <polygon class="ig" points="280,240 280,280 0,0 40,0" />
        <!-- inner spiral squares -->
        <rect
          class="ig2"
          x="180"
          y="0"
          width="30"
          height="30"
          transform="rotate(45 195 15)"
        />
        <rect
          class="ig3"
          x="220"
          y="40"
          width="20"
          height="20"
          transform="rotate(45 230 50)"
        />
        <rect
          class="ig"
          x="150"
          y="150"
          width="40"
          height="40"
          transform="rotate(45 170 170)"
        />
        <rect
          class="ig2"
          x="195"
          y="195"
          width="25"
          height="25"
          transform="rotate(45 207 207)"
        />
        <!-- row of triangles -->
        <polygon class="ig3" points="0,280 40,240 80,280" />
        <polygon class="ig2" points="80,280 120,240 160,280" />
        <polygon class="ig3" points="160,280 200,240 240,280" />
        <polygon class="ig" points="240,280 280,240 280,280" />
        <!-- interlocking L-shapes -->
        <polyline
          class="ig"
          fill="none"
          stroke="var(--color-primary-light)"
          stroke-width="4"
          points="20,260 20,230 50,230 50,200 80,200 80,170 110,170"
        />
        <polyline
          class="ig"
          fill="none"
          stroke="var(--color-primary-dark)"
          stroke-width="3"
          points="260,20 230,20 230,50 200,50 200,80 170,80 170,110"
        />
      </svg>

      <!-- Imigongo: bottom-left -->
      <svg
        class="imigongo imigongo--br"
        viewBox="0 0 240 240"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
      >
        <polygon class="ig" points="0,0 240,0 240,240" />
        <polygon class="ig2" points="0,0 220,0 0,220" />
        <polygon class="ig3" points="240,0 240,50 190,0" />
        <polygon class="ig" points="240,50 240,100 140,0 190,0" />
        <polygon class="ig2" points="240,100 240,150 90,0 140,0" />
        <polygon class="ig3" points="240,150 240,200 40,0 90,0" />
        <polygon class="ig" points="240,200 240,240 0,0 40,0" />
        <rect
          class="ig3"
          x="150"
          y="130"
          width="36"
          height="36"
          transform="rotate(45 168 148)"
        />
        <rect
          class="ig2"
          x="188"
          y="168"
          width="22"
          height="22"
          transform="rotate(45 199 179)"
        />
        <polygon class="ig3" points="0,240 35,205 70,240" />
        <polygon class="ig2" points="70,240 105,205 140,240" />
        <polygon class="ig3" points="140,240 175,205 210,240" />
        <polygon class="ig" points="210,240 240,210 240,240" />
        <polyline
          fill="none"
          stroke="var(--color-primary-light)"
          stroke-width="3.5"
          points="15,225 15,198 42,198 42,171 69,171 69,144 96,144"
        />
        <polyline
          fill="none"
          stroke="var(--color-primary-dark)"
          stroke-width="3"
          points="225,15 198,15 198,42 171,42 171,69 144,69 144,96"
        />
      </svg>

      <!-- ── GRID ── -->
      <div class="footer__inner">
        <!-- BRAND -->
        <div class="footer__col footer__brand">
          <a href="<?php echo $baseLink('home'); ?>" title="Virunga Collective - Main Homepage" style="display: inline-block;">
            <img
              src="./img/logo/logo.png"
              alt="Virunga Collective"
              class="footer__logo"
            />
          </a>
          <p class="footer__tagline">Where Virunga Becomes Personal.</p>
          <p class="footer__desc">
           Live the Virunga in a real home through people, stories, and shared moments that stay with you.
          </p>

          <div class="footer__badges">
            <span class="badge"
              ><i class="fa-solid fa-clock"></i> Est. 2020</span
            >
          </div>
        </div>

        <!-- EXPLORE -->
        <div class="footer__col">
          <h3 class="footer__col-title">Explore</h3>
          <ul class="footer__links">
            <li><a href="<?php echo $baseLink('home'); ?>"><i class="fas fa-globe" style="font-size: 0.8rem; margin-right: 4px; color: var(--color-primary);"></i> Collective Home</a></li>
            <li><a href="<?php echo $baseLink('homestays'); ?>">Virunga House</a></li>
            <li><a href="<?php echo $baseLink('about-us'); ?>">Our Story</a></li>
            <li><a href="<?php echo $baseLink('rooms'); ?>">Our Rooms</a></li>
            <li><a href="<?php echo $baseLink('shop'); ?>">Shop</a></li>
            <li><a href="<?php echo $baseLink('cars'); ?>">Car Rent</a></li>
            <li><a href="<?php echo $baseLink('activities'); ?>">Experiences</a></li>
            <li><a href="<?php echo $baseLink('impact'); ?>">Our Impact</a></li>
             <li><a href="<?php echo $baseLink('blog'); ?>">Blogs</a></li>
          </ul>
        </div>

        <!-- GUEST INFO -->
        <div class="footer__col">
          <h3 class="footer__col-title">Guest Info</h3>
          <ul class="footer__links">
            <li><a href="<?php echo $baseLink('rooms'); ?>">Book a Stay</a></li>
             <li><a href="<?php echo $baseLink('homestays'); ?>#who-its-for">Location &amp; Suitability</a></li>
            <li><a href="<?php echo $baseLink('houserules'); ?>">Check-in / Check-out</a></li>
            <li><a href="<?php echo $baseLink('houserules'); ?>">Our House Rules</a></li>
            <li><a href="<?php echo $baseLink('homestays'); ?>#hospitality-faq">FAQ</a></li>
            <li><a href="https://www.tripadvisor.com/Hotel_Review-g317075-d20326735-Reviews-Virunga_Homestay_Live_the_Virunga_Experience-Ruhengeri_Musanze_District_Northern_Prov.html" target="_blank" rel="noopener">Testimonials</a></li>
          </ul>
        </div>

        <!-- CONTACT -->
        <div class="footer__col">
          <h3 class="footer__col-title">Contact</h3>
          <div class="footer__contact">
            <div class="contact-item">
              <div class="contact-icon">
                <i class="fa-solid fa-location-dot"></i>
              </div>
              <div class="contact-text">
                <span class="contact-label">Address</span>
                <span class="contact-value"
                  >Musanze, Rwanda</span
                >
              </div>
            </div>

            <div class="contact-item">
              <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
              <div class="contact-text">
                <span class="contact-label">Phone / WhatsApp</span>
                <span class="contact-value"
                  ><a href="tel:+250784513435">+250 784 513 435</a></span
                >
              </div>
            </div>

            <div class="contact-item">
              <div class="contact-icon">
                <i class="fa-solid fa-envelope"></i>
              </div>
              <div class="contact-text">
                <span class="contact-label">Email</span>
                <span class="contact-value"
                  ><a href="mailto:info@virungajourneys.com"
                    >info@virungajourneys.com</a
                  ><br/>
                  <a href="mailto:virungahomestay@gmail.com"
                    >virungahomestay@gmail.com</a
                  ></span
                >
              </div>
            </div>
          </div>

          <!-- Social -->
          <div class="footer__social">
            <a href="" class="social-link" aria-label="Facebook"
              ><i class="fa-brands fa-facebook-f"></i
            ></a>
            <a href="" class="social-link" aria-label="Instagram"
              ><i class="fa-brands fa-instagram"></i
            ></a>
          </div>
        </div>
      </div>

      <!-- Divider -->
      <div class="footer__divider"><hr /></div>

      <!-- Bottom bar -->
      <div class="footer__bottom">
        <p class="footer__copy">
          © 2025 <span>Virunga Homestay</span>. All rights reserved.</p>
        <ul class="footer__bottom-links">
          <li><a href="<?php echo $baseLink('privacy'); ?>">Privacy Policy</a></li>
          <li><a href="<?php echo $baseLink('privacy'); ?>">Terms of Service</a></li>
          <li><a href="<?php echo $baseLink('privacy'); ?>">Cookie Policy</a></li>
        </ul>
      </div>
    </footer>

    <!-- ── WHATSAPP FLOATING BUTTON ─────────────────────────── -->
    <button type="button" id="ai-chat-btn" aria-label="Open Virunga Collective Assistant" title="Virunga Collective Assistant">
      <i class="fa-solid fa-comments"></i>
    </button>

    <section id="ai-chat-modal" class="ai-chat-hidden" aria-label="Virunga Collective Assistant">
      <div class="ai-chat-header">
        <div class="ai-chat-title">
          <span class="ai-status-dot"></span>
          <strong>Virunga Collective Assistant</strong>
        </div>
        <button type="button" class="ai-chat-close" aria-label="Close assistant">&times;</button>
      </div>

      <div class="ai-chat-messages" id="ai-messages">
        <div class="ai-msg bot-msg">
          Hello! How can I help you explore Virunga Collective's tours, stays, activities, or shop items today?
        </div>
      </div>

      <form class="ai-chat-input-area" id="ai-chat-form">
        <input type="text" id="ai-user-input" placeholder="Ask about tours, stays, activities..." autocomplete="off" />
        <button type="submit">Send</button>
      </form>
    </section>

    <style>
      #ai-chat-btn {
        position: fixed;
        bottom: 25px;
        right: 25px;
        width: 62px;
        height: 62px;
        background: linear-gradient(135deg, #123c2a 0%, #2e7d32 100%);
        color: #ffffff;
        border: 1px solid rgba(218, 177, 82, 0.55);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.45rem;
        box-shadow: 0 14px 34px rgba(18, 42, 31, 0.36);
        z-index: 99990;
        cursor: pointer;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
      }
      #ai-chat-btn:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 18px 42px rgba(18, 42, 31, 0.46);
      }
      #ai-chat-modal {
        position: fixed;
        bottom: 102px;
        right: 25px;
        width: 380px;
        max-width: calc(100vw - 32px);
        height: 510px;
        max-height: calc(100vh - 135px);
        background: #ffffff;
        border: 1px solid rgba(18, 42, 31, 0.12);
        border-radius: 12px;
        box-shadow: 0 18px 50px rgba(0, 0, 0, 0.22);
        display: flex;
        flex-direction: column;
        z-index: 99991;
        overflow: hidden;
        font-family: inherit;
      }
      .ai-chat-hidden { display: none !important; }
      .ai-chat-header {
        background: #122a1f;
        color: #f6f2e9;
        padding: 15px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
      }
      .ai-chat-title { display: flex; align-items: center; gap: 8px; min-width: 0; }
      .ai-chat-title strong { font-size: 0.98rem; line-height: 1.2; }
      .ai-status-dot {
        width: 8px;
        height: 8px;
        background: #4caf50;
        border-radius: 50%;
        flex: 0 0 auto;
      }
      .ai-chat-close {
        background: transparent;
        border: 0;
        color: #ffffff;
        font-size: 1.55rem;
        line-height: 1;
        cursor: pointer;
        padding: 0 2px;
      }
      .ai-chat-messages {
        flex: 1;
        padding: 14px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: #f8f7f2;
      }
      .ai-msg {
        max-width: 84%;
        padding: 10px 13px;
        border-radius: 14px;
        font-size: 0.9rem;
        line-height: 1.45;
        overflow-wrap: anywhere;
      }
      .bot-msg {
        background: #ffffff;
        color: #233127;
        align-self: flex-start;
        border: 1px solid rgba(18, 42, 31, 0.08);
        border-bottom-left-radius: 4px;
      }
      .user-msg {
        background: #2e7d32;
        color: #ffffff;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
      }
      .ai-chat-input-area {
        display: flex;
        gap: 8px;
        padding: 10px;
        border-top: 1px solid rgba(18, 42, 31, 0.12);
        background: #ffffff;
      }
      .ai-chat-input-area input {
        flex: 1;
        min-width: 0;
        border: 1px solid rgba(18, 42, 31, 0.22);
        border-radius: 20px;
        padding: 9px 14px;
        outline: none;
        font-size: 0.9rem;
        font-family: inherit;
      }
      .ai-chat-input-area input:focus { border-color: #2e7d32; }
      .ai-chat-input-area button {
        background: #1b4332;
        color: #ffffff;
        border: 0;
        border-radius: 20px;
        padding: 9px 15px;
        cursor: pointer;
        font-size: 0.9rem;
        font-family: inherit;
      }
      .ai-chat-input-area button:disabled,
      .ai-chat-input-area input:disabled {
        opacity: 0.65;
        cursor: wait;
      }
      @media (max-width: 768px) {
        #ai-chat-btn {
          bottom: 20px;
          right: 20px;
          width: 54px;
          height: 54px;
          font-size: 1.25rem;
        }
        #ai-chat-modal {
          bottom: 86px;
          right: 16px;
          height: 460px;
        }
      }
    </style>
    <script>
      (() => {
        const endpoint = "<?php echo isset($baseLink) ? htmlspecialchars($baseLink('chat_api.php'), ENT_QUOTES) : '../chat_api.php'; ?>";
        const button = document.getElementById('ai-chat-btn');
        const modal = document.getElementById('ai-chat-modal');
        const close = modal?.querySelector('.ai-chat-close');
        const form = document.getElementById('ai-chat-form');
        const input = document.getElementById('ai-user-input');
        const messages = document.getElementById('ai-messages');

        const chatHistory = [];

        const appendMessage = (content, type, isHTML = false) => {
          const div = document.createElement('div');
          div.className = `ai-msg ${type}-msg`;
          if (isHTML) {
            div.innerHTML = content;
          } else {
            div.textContent = content;
          }
          messages.appendChild(div);
          messages.scrollTop = messages.scrollHeight;
          return div;
        };

        const toggleChat = () => {
          modal.classList.toggle('ai-chat-hidden');
          if (!modal.classList.contains('ai-chat-hidden')) input.focus();
        };

        button?.addEventListener('click', toggleChat);
        close?.addEventListener('click', toggleChat);

        const parseMarkdown = (text) => {
          if (!text) return '';
          let str = text.trim();

          // Clean up stray markdown artifacts like :* or * :
          str = str.replace(/^[:*#\s]+/gm, '');

          str = str
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/__(.*?)__/g, '<strong>$1</strong>')
            .replace(/\*([^\*]+)\*/g, '<em>$1</em>')
            .replace(/^[\s]*[-•*]\s+(.*)$/gm, '• $1')
            .replace(/\n/g, '<br>');

          return str;
        };

        form?.addEventListener('submit', async (event) => {
          event.preventDefault();
          const text = input.value.trim();
          if (!text) return;

          appendMessage(text, 'user');
          chatHistory.push({ role: 'user', text: text });

          input.value = '';
          input.disabled = true;
          form.querySelector('button').disabled = true;
          const botMessage = appendMessage('<span class="wa-typing-container">typing <span class="wa-typing-dots"><span class="wa-dot"></span><span class="wa-dot"></span><span class="wa-dot"></span></span></span>', 'bot', true);

          try {
            const response = await fetch(endpoint, {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ message: text, history: chatHistory.slice(-8) }),
            });
            const data = await response.json();
            const reply = data.response || 'Sorry, I could not process that right now. Please contact us directly through our Contact page.';
            botMessage.innerHTML = parseMarkdown(reply);
            chatHistory.push({ role: 'bot', text: reply });
          } catch (error) {
            botMessage.textContent = 'Sorry, I am having trouble connecting right now. Please try again later or contact us directly through our Contact page.';
          } finally {
            input.disabled = false;
            form.querySelector('button').disabled = false;
            input.focus();
            messages.scrollTop = messages.scrollHeight;
          }
        });
      })();
    </script>
    <!-- SCRIPTS -->
    <script src="js/main.js"></script>
    <script src="js/count.js"></script>
    <?php if (!empty($pageScripts) && is_array($pageScripts)) foreach ($pageScripts as $js): ?>
      <script src="js/<?php echo htmlspecialchars($js); ?>"></script>
    <?php endforeach; ?>
  </body>
</html>
