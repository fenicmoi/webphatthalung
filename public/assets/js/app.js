/**
 * Modern Interactive Application Script (Vanilla JS + SPA utilities)
 * Handles Dark/Light mode switching, Asynchronous Fetch API with CSRF protection, and UI Toasts.
 */

const App = (function() {
    'use strict';

    // 1. Theme Manager (Dark/Light Mode)
    const ThemeManager = {
        init: function() {
            const savedTheme = localStorage.getItem('app_theme') || this.getSystemPreference();
            this.setTheme(savedTheme, false);
            
            const toggleBtn = document.getElementById('theme-toggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => this.toggle());
            }
        },
        getSystemPreference: function() {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        },
        setTheme: function(theme, notify = true) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('app_theme', theme);
            
            // Update Toggle Icon if applicable
            const toggleBtn = document.getElementById('theme-toggle');
            if (toggleBtn) {
                toggleBtn.innerHTML = theme === 'dark' ? '<i class="fa-solid fa-sun text-warning"></i>' : '<i class="fa-solid fa-moon text-indigo-500"></i>';
            }
            if (notify) {
                App.toast(`เปลี่ยนเป็น${theme === 'dark' ? 'โหมดกลางคืน 🌙' : 'โหมดกลางวัน ☀️'} เรียบร้อยแล้ว`, 'info');
            }
        },
        toggle: function() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const nextTheme = currentTheme === 'light' ? 'dark' : 'light';
            this.setTheme(nextTheme, true);
        }
    };

    // 2. Toast Notification Helper (No heavy dependencies required)
    const ToastManager = {
        show: function(message, type = 'success') {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            toast.className = 'modern-toast';

            // Select appropriate icon
            let iconHtml = '<i class="fa-solid fa-check-circle text-success" style="color: var(--accent-success); font-size: 1.4rem;"></i>';
            if (type === 'info') {
                iconHtml = '<i class="fa-solid fa-info-circle text-primary" style="color: var(--accent-primary); font-size: 1.4rem;"></i>';
            } else if (type === 'error' || type === 'danger') {
                iconHtml = '<i class="fa-solid fa-triangle-exclamation text-danger" style="color: var(--accent-danger); font-size: 1.4rem;"></i>';
            }

            toast.innerHTML = `
                <div>${iconHtml}</div>
                <div style="flex: 1; font-weight: 500; font-size: 0.95rem;">${message}</div>
            `;

            container.appendChild(toast);

            // Auto dismiss after 3.5 seconds
            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.35s forwards';
                setTimeout(() => toast.remove(), 350);
            }, 3500);
        }
    };

    // 3. Asynchronous Fetch API Helper with auto CSRF injection
    const FetchHelper = async function(url, options = {}) {
        options.headers = options.headers || {};
        options.headers['X-Requested-With'] = 'XMLHttpRequest';
        options.headers['Accept'] = 'application/json';

        // Check for CI4 CSRF token in HTML meta tag
        const csrfMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
        const csrfHeader = document.querySelector('meta[name="X-CSRF-HEADER"]');
        if (csrfMeta && csrfHeader) {
            options.headers[csrfHeader.content] = csrfMeta.content;
        }

        try {
            const response = await fetch(url, options);
            
            // Check if CSRF token returned in header for update
            const newCsrf = response.headers.get('X-CSRF-TOKEN');
            if (newCsrf && csrfMeta) {
                csrfMeta.content = newCsrf;
            }

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Fetch API Error:', error);
            App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อข้อมูล: ' + error.message, 'error');
            throw error;
        }
    };

    // 4. Universal Smart Search Engine (Omni-Search & Thai Voice Speech AI)
    const OmniSearch = {
        debounceTimer: null,
        modalInstance: null,
        init: function() {
            // Register Keyboard Shortcut (Ctrl+K or Cmd+K)
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
                    e.preventDefault();
                    OmniSearch.open();
                }
            });

            const input = document.getElementById('omniSearchInput');
            if (input) {
                input.addEventListener('input', function(e) {
                    clearTimeout(OmniSearch.debounceTimer);
                    const query = e.target.value;
                    OmniSearch.debounceTimer = setTimeout(() => {
                        OmniSearch.doSearch(query);
                    }, 280);
                });
            }
        },
        open: function(defaultQuery = '') {
            const modalEl = document.getElementById('omniSearchModal');
            if (!modalEl) return;

            if (!this.modalInstance && typeof bootstrap !== 'undefined') {
                this.modalInstance = new bootstrap.Modal(modalEl);
            }
            if (this.modalInstance) this.modalInstance.show();

            const input = document.getElementById('omniSearchInput');
            if (input) {
                input.value = defaultQuery;
                setTimeout(() => input.focus(), 250);
            }

            this.doSearch(defaultQuery);
        },
        quickSearch: function(keyword) {
            const input = document.getElementById('omniSearchInput');
            if (input) input.value = keyword;
            this.doSearch(keyword);
        },
        doSearch: async function(keyword) {
            const trendingSec = document.getElementById('omniTrendingSection');
            const resultsDiv = document.getElementById('omniResultsContainer');
            if (!resultsDiv) return;

            if (!keyword || keyword.trim() === '') {
                if (trendingSec) trendingSec.style.display = 'block';
            } else {
                if (trendingSec) trendingSec.style.display = 'none';
            }

            resultsDiv.innerHTML = '<div class="text-center py-5"><i class="fa-solid fa-spinner fa-spin fs-2 text-warning"></i><p class="text-muted mt-2">กำลังค้นหาข้อมูลทั่วเว็บไซต์...</p></div>';

            try {
                const baseUrl = window.BASE_URL || (document.querySelector('meta[name="base_url"]') ? document.querySelector('meta[name="base_url"]').content : '');
                const url = baseUrl + '/api/search?q=' + encodeURIComponent(keyword || '');
                const data = await FetchHelper(url);

                if (data.status === 'success') {
                    if (data.total === 0) {
                        resultsDiv.innerHTML = `
                            <div class="text-center py-5 text-secondary">
                                <i class="fa-solid fa-file-circle-question fs-1 mb-3 text-warning opacity-75"></i>
                                <h5 class="text-white">ไม่พบข้อความที่ตรงกับ "${keyword}"</h5>
                                <p class="small text-muted">ลองค้นหาด้วยคำอื่น เช่น 'ภาษี', 'ทะเลน้อย', 'ร้องทุกข์', หรือ 'PDPA'</p>
                            </div>
                        `;
                        return;
                    }

                    let html = '';
                    const cats = data.categories || {};
                    for (const [key, group] of Object.entries(cats)) {
                        html += `
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3 d-flex align-items-center justify-content-between text-warning border-bottom pb-2" style="border-color: rgba(255,255,255,0.1) !important;">
                                    <span>${group.label}</span>
                                    <span class="badge bg-secondary rounded-pill">${group.items.length} รายการ</span>
                                </h6>
                                <div class="list-group">
                        `;
                        group.items.forEach(item => {
                            html += `
                                <a href="${item.url}" onclick="OmniSearch.handleSelect('${item.title}');" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between p-3 mb-2 rounded-3 text-white border transition-all omni-result-card text-decoration-none" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.12) !important;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-3 rounded-circle text-center d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 48px; height: 48px; background: rgba(96, 165, 250, 0.18);">
                                            <i class="${item.icon || 'fa-solid fa-star'} fs-4 text-info"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <strong class="fs-6 text-light">${item.title}</strong>
                                                ${item.badge ? `<span class="badge bg-dark border border-secondary text-warning px-2 py-0" style="font-size: 0.75rem;">${item.badge}</span>` : ''}
                                            </div>
                                            <p class="text-secondary m-0 small" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">${item.description}</p>
                                        </div>
                                    </div>
                                    <div class="text-end ms-3 d-none d-sm-block flex-shrink-0">
                                        <span class="btn btn-sm btn-outline-info rounded-pill px-3 py-1 text-nowrap"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>เข้าถึงบริการ</span>
                                    </div>
                                </a>
                            `;
                        });
                        html += `
                                </div>
                            </div>
                        `;
                    }
                    resultsDiv.innerHTML = html;
                }
            } catch (err) {
                console.error('OmniSearch query error:', err);
                resultsDiv.innerHTML = '<div class="alert alert-danger">ไม่สามารถโหลดข้อมูลการค้นหาได้ในขณะนี้</div>';
            }
        },
        handleSelect: function(title) {
            const modalEl = document.getElementById('omniSearchModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            App.toast('🚀 นำท่านเข้าสู่บริการ: ' + title, 'success');
        },
        startVoiceSearch: function() {
            if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                App.toast('❌ เบราว์เซอร์ของคุณไม่รองรับการสั่งงานด้วยเสียง (แนะนำ Google Chrome/Edge)', 'danger');
                return;
            }
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            const recognition = new SpeechRecognition();
            recognition.lang = 'th-TH';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            const btnVoice = document.getElementById('btnVoiceSearch');
            if (btnVoice) {
                btnVoice.classList.add('bg-danger', 'text-white');
                btnVoice.innerHTML = '<i class="fa-solid fa-microphone-lines fs-5 animate-pulse"></i>';
            }
            App.toast('🎤 กรุณาพูดคำต้องการค้นหา เช่น "ภาษี" หรือ "ทะเลน้อย"...', 'info');

            recognition.start();

            recognition.onresult = function(event) {
                const speechResult = event.results[0][0].transcript;
                const input = document.getElementById('omniSearchInput');
                if (input) input.value = speechResult;
                App.toast('🎯 คาดเดาเสียงเป็นคำว่า: "' + speechResult + '"', 'success');
                OmniSearch.doSearch(speechResult);
            };

            recognition.onend = function() {
                if (btnVoice) {
                    btnVoice.classList.remove('bg-danger', 'text-white');
                    btnVoice.innerHTML = '<i class="fa-solid fa-microphone fs-5"></i>';
                }
            };

            recognition.onerror = function(event) {
                console.error('Speech recognition error:', event.error);
                App.toast('ไม่สามารถรับข้อมูลเสียงได้ กรุณาลองพูดใหม่อีกครั้ง', 'danger');
                if (btnVoice) {
                    btnVoice.classList.remove('bg-danger', 'text-white');
                    btnVoice.innerHTML = '<i class="fa-solid fa-microphone fs-5"></i>';
                }
            };
        }
    };

    // Initialize application on DOM content loaded
    document.addEventListener('DOMContentLoaded', function() {
        ThemeManager.init();
        OmniSearch.init();
        console.log('🚀 Modern CI4 Portal Application Initialized successfully!');
    });

    // Public API Methods
    return {
        theme: ThemeManager,
        toast: ToastManager.show,
        fetch: FetchHelper,
        omni: OmniSearch
    };
})();

// Assign globally for easy access
window.App = App;
window.OmniSearch = App.omni;

