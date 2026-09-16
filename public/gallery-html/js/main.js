/**
 * คลังภาพกิจกรรมจังหวัดพัทลุง
 * Main JavaScript ES6+
 */

// 11. Gallery Data (อย่างน้อย 9 รายการ)
const galleryData = [
  {
    id: 1,
    title: "งานประเพณีแห่ผ้าพระบฏสลากพระ จังหวัดพัทลุง ประจำปี 2569",
    category: "ประเพณีและวัฒนธรรม",
    date: "25 ม.ค. 2568",
    location: "วัดคูหาสวรรค์ พระอารามหลวง อ.เมืองพัทลุง",
    description: "กิจกรรมสำคัญของจังหวัดที่สะท้อนเอกลักษณ์ทางวัฒนธรรมและพลังศรัทธาของชาวพัทลุง โดยมีพิธีสมโภชและขบวนแห่ผ้าพระบฏอันศักดิ์สิทธิ์",
    image: "https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?auto=format&fit=crop&w=800&q=80",
    photoCount: 24
  },
  {
    id: 2,
    title: "งานสืบสานศิลป์ถิ่นโนรา โรงครูวัดท่าแค มรดกภูมิปัญญาทางวัฒนธรรม",
    category: "ประเพณีและวัฒนธรรม",
    date: "18 ม.ค. 2568",
    location: "วัดท่าแค ต.ท่าแค อ.เมืองพัทลุง",
    description: "พิธีกรรมโนราโรงครูที่ยิ่งใหญ่ที่สุดในภาคใต้ บูชาครูหมอโนราและสืบทอดสายเลือดศิลปินพื้นบ้านพัทลุงที่ได้รับการยกย่องเป็นมรดกโลกยูเนสโก",
    image: "https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=800&q=80",
    photoCount: 36
  },
  {
    id: 3,
    title: "เทศกาลล่องแก่งหนานมดแดง ส่งเสริมการท่องเที่ยวเชิงนิเวศน์พัทลุง",
    category: "ท่องเที่ยวและเศรษฐกิจ",
    date: "12 ม.ค. 2568",
    location: "ลานกิจกรรมหนานมดแดง อ.ป่าพะยอม",
    description: "กิจกรรมล่องแก่งผจญภัยสัมผัสสายน้ำใสและธรรมชาติบริสุทธิ์ของเทือกเขาบรรทัด กระตุ้นเศรษฐกิจชุมชนและประชาสัมพันธ์การท่องเที่ยว",
    image: "https://images.unsplash.com/photo-1530866495561-507c9faab2ed?auto=format&fit=crop&w=800&q=80",
    photoCount: 18
  },
  {
    id: 4,
    title: "กิจกรรมจิตอาสาพัฒนาภูมิทัศน์หาดลำปำ เฉลิมพระเกียรติ",
    category: "กิจกรรมสาธารณประโยชน์",
    date: "8 ม.ค. 2568",
    location: "หาดแสนสุขลำปำ อ.เมืองพัทลุง",
    description: "ผู้ว่าราชการจังหวัดพัทลุงนำหัวหน้าส่วนราชการและจิตอาสาพระราชทานร่วมบำเพ็ญสาธารณประโยชน์ ฟื้นฟูทัศนียภาพริมทะเลสาบสงขลา",
    image: "https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=800&q=80",
    photoCount: 15
  },
  {
    id: 5,
    title: "การแข่งขันเรือพายประเพณีลุ่มน้ำทะเลน้อย ชิงถ้วยพระราชทาน",
    category: "ประเพณีและวัฒนธรรม",
    date: "28 ธ.ค. 2567",
    location: "เขตห้ามล่าสัตว์ป่าทะเลน้อย อ.ควนขนุน",
    description: "การแข่งขันเรือยาวพื้นบ้านท่ามกลางดงดอกบัวแดงและฝูงนกน้ำ สร้างความสามัคคีและส่งเสริมวิถีชีวิตชาวประมงลุ่มน้ำพัทลุง",
    image: "https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=800&q=80",
    photoCount: 28
  },
  {
    id: 6,
    title: "ตลาดป่าไผ่สร้างสุข แหล่งเรียนรู้เกษตรอินทรีย์และวัฒนธรรมอาหาร",
    category: "ท่องเที่ยวและเศรษฐกิจ",
    date: "21 ธ.ค. 2567",
    location: "สวนไผ่ขวัญใจ อ.ควนขนุน",
    description: "ตลาดสีเขียวปลอดโฟมและพลาสติก รวบรวมอาหารพื้นบ้าน ขนมโบราณ และสินค้าหัตถกรรมกระจูดอันเลื่องชื่อของจังหวัดพัทลุง",
    image: "https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80",
    photoCount: 22
  },
  {
    id: 7,
    title: "โครงการอบรมทักษะดิจิทัลและนวัตกรรม AI เพื่อเยาวชนพัทลุงก้าวหน้า",
    category: "การศึกษานวัตกรรม",
    date: "15 ธ.ค. 2567",
    location: "หอประชุมศาลากลางจังหวัดพัทลุง",
    description: "เสริมสร้างองค์ความรู้ด้านปัญญาประดิษฐ์และเทคโนโลยีสมัยใหม่แก่ครูและนักเรียน เพื่อยกระดับสู่การเป็น Smart City ระดับภูมิภาค",
    image: "https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80",
    photoCount: 19
  },
  {
    id: 8,
    title: "พิธีบวงสรวงศาลหลักเมืองพัทลุงและสมโภชพระพุทธนิรโรคันตรายชัยวัฒน์จตุรทิศ",
    category: "ประเพณีและวัฒนธรรม",
    date: "5 ธ.ค. 2567",
    location: "ศาลหลักเมืองพัทลุง อ.เมืองพัทลุง",
    description: "พิธีพราหมณ์และพิธีสงฆ์เพื่อความเป็นสิริมงคลแก่บ้านเมืองและประชาชน โดยมีผู้ว่าราชการจังหวัดเป็นประธานในพิธี",
    image: "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80",
    photoCount: 30
  },
  {
    id: 9,
    title: "เทศกาลข้าวสังข์หยดเมืองพัทลุง สินค้าสิ่งบ่งชี้ทางภูมิศาสตร์ (GI)",
    category: "ท่องเที่ยวและเศรษฐกิจ",
    date: "25 พ.ย. 2567",
    location: "ศูนย์แสดงสินค้าโอทอป (OTOP) พัทลุง",
    description: "จัดแสดงและจำหน่ายข้าวสังข์หยดพัทลุง ข้าว GI พันธุ์แรกของประเทศไทย พร้อมนิทรรศการแปรรูปสินค้าเพื่อสุขภาพและการส่งออก",
    image: "https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=800&q=80",
    photoCount: 25
  }
];

// State Management
let currentCategory = "ทั้งหมด";
let searchQuery = "";
let currentFontScale = 1;

// DOM Elements
const galleryGrid = document.getElementById("galleryGrid");
const searchInput = document.getElementById("searchInput");
const searchBtn = document.getElementById("searchBtn");
const filterPills = document.querySelectorAll(".btn-filter-pill");
const emptyState = document.getElementById("emptyState");
const resetFilterBtn = document.getElementById("resetFilterBtn");
const backToTopBtn = document.getElementById("backToTopBtn");
const darkModeToggle = document.getElementById("darkModeToggle");
const fontButtons = document.querySelectorAll(".btn-font-size");
const navbar = document.querySelector(".gov-navbar");

// Modal Elements
const detailModal = new bootstrap.Modal(document.getElementById("detailModal"));
const modalImage = document.getElementById("modalImage");
const modalTitle = document.getElementById("modalTitle");
const modalCategory = document.getElementById("modalCategory");
const modalDate = document.getElementById("modalDate");
const modalLocation = document.getElementById("modalLocation");
const modalDescription = document.getElementById("modalDescription");
const modalPhotoCount = document.getElementById("modalPhotoCount");

// Initialize
document.addEventListener("DOMContentLoaded", () => {
  renderGallery();
  initNavbarScroll();
  initBackToTop();
  initDarkMode();
  initFontSize();
  initSearch();
  initFilters();
});

// Render Gallery Cards
function renderGallery() {
  const filtered = galleryData.filter((item) => {
    const matchesCat =
      currentCategory === "ทั้งหมด" || item.category === currentCategory;
    const query = searchQuery.toLowerCase().trim();
    const matchesSearch =
      !query ||
      item.title.toLowerCase().includes(query) ||
      item.category.toLowerCase().includes(query) ||
      item.description.toLowerCase().includes(query) ||
      item.location.toLowerCase().includes(query);

    return matchesCat && matchesSearch;
  });

  galleryGrid.innerHTML = "";

  if (filtered.length === 0) {
    emptyState.classList.remove("d-none");
  } else {
    emptyState.classList.add("d-none");
    filtered.forEach((item) => {
      const cardCol = document.createElement("div");
      cardCol.className = "col-xl-4 col-lg-4 col-md-6 col-12";
      cardCol.innerHTML = `
        <article class="gallery-card h-100">
          <div class="card-image-wrap">
            <img src="${item.image}" alt="${item.title}" class="card-image" loading="lazy">
            <span class="badge-category">${item.category}</span>
            <span class="badge-date"><i class="fa-regular fa-calendar me-1"></i>${item.date}</span>
            <span class="badge-count"><i class="fa-solid fa-camera me-1"></i>${item.photoCount} ภาพ</span>
          </div>
          <div class="gallery-card-body">
            <div>
              <h3 class="card-title-text">${item.title}</h3>
              <p class="card-desc-text">${item.description}</p>
              <div class="card-location-text">
                <i class="fa-solid fa-location-dot"></i>
                <span>${item.location}</span>
              </div>
            </div>
            <div class="card-footer-action">
              <button type="button" class="btn-detail-link" onclick="openDetailModal(${item.id})">
                ดูรายละเอียด <i class="fa-solid fa-arrow-right-long ms-1"></i>
              </button>
            </div>
          </div>
        </article>
      `;
      galleryGrid.appendChild(cardCol);
    });
  }
}

// Open Detail Modal
window.openDetailModal = function(id) {
  const item = galleryData.find(g => g.id === id);
  if (!item) return;

  modalImage.src = item.image;
  modalImage.alt = item.title;
  modalTitle.textContent = item.title;
  modalCategory.textContent = item.category;
  modalDate.textContent = item.date;
  modalLocation.textContent = item.location;
  modalDescription.textContent = item.description;
  modalPhotoCount.textContent = `${item.photoCount} ภาพกิจกรรม`;

  detailModal.show();
};

// Filter handling
function initFilters() {
  filterPills.forEach((btn) => {
    btn.addEventListener("click", () => {
      filterPills.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      currentCategory = btn.getAttribute("data-category") || "ทั้งหมด";
      renderGallery();
    });
  });

  if (resetFilterBtn) {
    resetFilterBtn.addEventListener("click", () => {
      currentCategory = "ทั้งหมด";
      searchQuery = "";
      if (searchInput) searchInput.value = "";
      filterPills.forEach((b) => {
        if (b.getAttribute("data-category") === "ทั้งหมด") b.classList.add("active");
        else b.classList.remove("active");
      });
      renderGallery();
    });
  }
}

// Real-time Search
function initSearch() {
  if (searchInput) {
    searchInput.addEventListener("input", (e) => {
      searchQuery = e.target.value;
      renderGallery();
    });
  }
  if (searchBtn) {
    searchBtn.addEventListener("click", () => {
      if (searchInput) {
        searchQuery = searchInput.value;
        renderGallery();
      }
    });
  }
}

// Navbar shadow on scroll
function initNavbarScroll() {
  window.addEventListener("scroll", () => {
    if (window.scrollY > 20) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  });
}

// Back to top button
function initBackToTop() {
  if (!backToTopBtn) return;
  window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
      backToTopBtn.classList.add("visible");
    } else {
      backToTopBtn.classList.remove("visible");
    }
  });

  backToTopBtn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
}

// Dark Mode Toggle
function initDarkMode() {
  const savedTheme = localStorage.getItem("gov_theme") || "light";
  document.documentElement.setAttribute("data-bs-theme", savedTheme);
  updateDarkModeIcon(savedTheme);

  if (darkModeToggle) {
    darkModeToggle.addEventListener("click", (e) => {
      e.preventDefault();
      const currentTheme = document.documentElement.getAttribute("data-bs-theme");
      const newTheme = currentTheme === "dark" ? "light" : "dark";
      document.documentElement.setAttribute("data-bs-theme", newTheme);
      localStorage.setItem("gov_theme", newTheme);
      updateDarkModeIcon(newTheme);
    });
  }
}

function updateDarkModeIcon(theme) {
  if (!darkModeToggle) return;
  if (theme === "dark") {
    darkModeToggle.innerHTML = `<i class="fa-solid fa-sun me-1"></i> โหมดสว่าง`;
  } else {
    darkModeToggle.innerHTML = `<i class="fa-solid fa-moon me-1"></i> โหมดมืด`;
  }
}

// Font Size Adjustment
function initFontSize() {
  fontButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      fontButtons.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      const scale = parseFloat(btn.getAttribute("data-size")) || 1;
      document.documentElement.style.setProperty("--font-scale", scale);
    });
  });
}
