document.addEventListener('DOMContentLoaded', () => {
  const html = document.documentElement;

  console.log('APP.JS LOADED');

  // ----- DARK MODE -----
  const toggle = document.getElementById('darkModeToggle');
  const saved = localStorage.getItem('theme');

  if (saved === 'dark') html.classList.add('dark');
  else if (saved === 'light') html.classList.remove('dark');

  if (toggle) {
    console.log('DARK BUTTON FOUND:', toggle);
    toggle.addEventListener('click', () => {
      const isDark = html.classList.toggle('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });
  }

  // ----- TOGGLE RIGHT SIDEBAR -----
  const infoToggleBtn = document.getElementById('infoToggleBtn');
  const infoPanel = document.getElementById('cloInfoPanel');

  console.log('INFO BTN:', infoToggleBtn);
  console.log('INFO PANEL:', infoPanel);

  if (infoToggleBtn && infoPanel) {
    infoToggleBtn.addEventListener('click', () => {
      const isHidden = infoPanel.classList.contains('hidden');

      if (isHidden) {
        infoPanel.classList.remove('hidden');
        infoPanel.classList.add('flex', 'flex-col');
      } else {
        infoPanel.classList.add('hidden');
        infoPanel.classList.remove('flex', 'flex-col');
      }

      console.log('TOGGLE PANEL. Now hidden?', infoPanel.classList.contains('hidden'));
    });
  }

  // ----- PROFILE POPUP -----
  const btn = document.getElementById("profileBtn");
  const popup = document.getElementById("profilePopup");
  const overlay = document.getElementById("profileOverlay");

  console.log('PROFILE BTN:', btn);
  console.log('PROFILE POPUP:', popup);
  console.log('PROFILE OVERLAY:', overlay);

  if (!btn || !popup || !overlay) return;

  const open = () => {
    popup.classList.remove("hidden");
    overlay.classList.remove("hidden");
  };

  const close = () => {
    popup.classList.add("hidden");
    overlay.classList.add("hidden");
  };

  const isOpen = () => !popup.classList.contains("hidden");

  btn.addEventListener("click", (e) => {
    e.stopPropagation();
    isOpen() ? close() : open();
  });

  overlay.addEventListener("click", close);

  document.addEventListener("click", (e) => {
    if (!isOpen()) return;
    if (popup.contains(e.target) || btn.contains(e.target)) return;
    close();
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") close();
  });
});

document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("chatInput");
    const sendBtn = document.getElementById("sendBtn");
    const messages = document.getElementById("chatMessages");

    function sendMessage() {
        const text = input.value.trim();
        if (!text) return;

        // User bubble
        messages.innerHTML += `
            <div class="flex items-start justify-end gap-3">
                <div class="max-w-xl rounded-2xl bg-[#B8352E] text-white shadow-sm px-4 py-3 text-sm">
                    <p>${text}</p>
                </div>
                <div class="w-9 h-9 rounded-full bg-slate-300 flex items-center justify-center text-xs font-semibold">
                    U
                </div>
            </div>
        `;

        input.value = "";
        messages.scrollTop = messages.scrollHeight;

        // Dummy bot reply
        setTimeout(() => {
            messages.innerHTML += `
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#B8352E] flex items-center justify-center text-white text-sm">
                        🤖
                    </div>
                    <div class="max-w-xl rounded-2xl bg-white dark:bg-slate-900 shadow-sm px-4 py-3 text-sm text-slate-800 dark:text-slate-100">
                        <p><span class="font-semibold">EduChat:</span> Nanti chatbot akan menjawab, sekarang belum terhubung ke backend :)... SEMANGAT KHANSA </p>
                    </div>
                </div>
            `;
            messages.scrollTop = messages.scrollHeight;
        }, 500);
    }

    // ENTER = send, SHIFT+ENTER = newline
    input.addEventListener("keydown", function (e) {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    sendBtn?.addEventListener("click", sendMessage);
});

document.querySelectorAll(".suggest-msg").forEach(btn => {
    btn.addEventListener("click", () => {
        const text = btn.innerText.trim();
        if (!text) return;

        // Set ke input
        document.getElementById("chatInput").value = text;

        // Trigger sendMessage()
        const sendEvent = new KeyboardEvent("keydown", {
            key: "Enter",
            shiftKey: false
        });

        document.getElementById("chatInput").dispatchEvent(sendEvent);
    });

    const dropzone   = document.getElementById("dropzone");
  const fileInput  = document.getElementById("fileInput");
  const browseBtn  = document.getElementById("browseBtn");
  const fileGrid   = document.getElementById("fileGrid");
  const emptyState = document.getElementById("emptyState");

  if (!dropzone || !fileInput || !fileGrid || !emptyState) return;

  const filesState = []; // simpan File object (dummy)

  const refreshEmptyState = () => {
    emptyState.style.display = filesState.length ? "none" : "block";
  };

  const truncateName = (name, max = 14) => {
    if (name.length <= max) return name;
    const parts = name.split(".");
    const ext = parts.length > 1 ? "." + parts.pop() : "";
    const base = parts.join(".");
    return base.slice(0, max) + "…" + ext;
  };

  const renderFileItem = (file, index) => {
    const item = document.createElement("div");
    item.className = "group flex flex-col items-center gap-2";

    // icon
    const icon = document.createElement("div");
    icon.className =
      "w-12 h-12 rounded-2xl bg-white/70 dark:bg-slate-800 flex items-center justify-center border border-slate-200/60 dark:border-slate-700";
    icon.textContent = "📕"; // pdf-ish

    // name
    const label = document.createElement("p");
    label.className = "text-[11px] text-slate-700 dark:text-slate-200 text-center break-words";
    label.textContent = truncateName(file.name, 16);

    // remove button (muncul saat hover)
    const remove = document.createElement("button");
    remove.type = "button";
    remove.className =
      "mt-1 text-[10px] px-2 py-1 rounded-full border border-slate-200 dark:border-slate-700 " +
      "text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 opacity-0 group-hover:opacity-100 transition";
    remove.textContent = "Hapus";
    remove.addEventListener("click", () => {
      filesState.splice(index, 1);
      rerenderGrid();
    });

    item.appendChild(icon);
    item.appendChild(label);
    item.appendChild(remove);
    return item;
  };

  const rerenderGrid = () => {
    fileGrid.innerHTML = "";
    filesState.forEach((f, i) => fileGrid.appendChild(renderFileItem(f, i)));
    refreshEmptyState();
  };

  const addFiles = (fileList) => {
    const incoming = Array.from(fileList || []);
    if (!incoming.length) return;

    // filter sederhana (optional)
    const allowed = [".pdf", ".ppt", ".pptx", ".doc", ".docx"];
    const filtered = incoming.filter(f => {
      const lower = f.name.toLowerCase();
      return allowed.some(ext => lower.endsWith(ext));
    });

    // push ke state
    filtered.forEach(f => filesState.push(f));

    rerenderGrid();
  };

  // klik "Pilih File"
  if (browseBtn) {
    browseBtn.addEventListener("click", () => fileInput.click());
  }

  // input change
  fileInput.addEventListener("change", (e) => {
    addFiles(e.target.files);
    fileInput.value = ""; // reset
  });

  // drag states
  const setActive = (active) => {
    if (active) {
      dropzone.classList.add("ring-4", "ring-[#9D1535]/10");
      dropzone.classList.add("bg-[#FFF7F7]");
    } else {
      dropzone.classList.remove("ring-4", "ring-[#9D1535]/10");
      dropzone.classList.remove("bg-[#FFF7F7]");
    }
  };

  dropzone.addEventListener("dragenter", (e) => {
    e.preventDefault();
    setActive(true);
  });

  dropzone.addEventListener("dragover", (e) => {
    e.preventDefault();
    setActive(true);
  });

  dropzone.addEventListener("dragleave", () => setActive(false));

  dropzone.addEventListener("drop", (e) => {
    e.preventDefault();
    setActive(false);
    addFiles(e.dataTransfer.files);
  });

  // init
  refreshEmptyState();

});

// ===== DOSEN MATERI CREATE: Dropzone =====
const dropzone = document.getElementById("dropzone");
const fileInput = document.getElementById("fileInput");
const fileGrid = document.getElementById("fileGrid");
const emptyHint = document.getElementById("emptyHint");

if (dropzone && fileInput && fileGrid && emptyHint) {
  const filesState = []; // frontend state

  const render = () => {
    fileGrid.innerHTML = "";

    if (filesState.length === 0) {
      emptyHint.classList.remove("hidden");
      return;
    }
    emptyHint.classList.add("hidden");

    filesState.forEach((file, idx) => {
      const item = document.createElement("div");
      item.className = "flex flex-col items-center text-center gap-2";

      item.innerHTML = `
        <div class="w-12 h-12 flex items-center justify-center">
          <span class="text-4xl">📕</span>
        </div>
        <p class="text-xs font-semibold text-slate-900 dark:text-slate-100 truncate w-full" title="${file.name}">
          ${file.name}
        </p>
        <button type="button"
                class="text-xs text-rose-600 hover:underline"
                data-remove="${idx}">
          Hapus
        </button>
      `;

      fileGrid.appendChild(item);
    });

    // remove handler
    fileGrid.querySelectorAll("[data-remove]").forEach(btn => {
      btn.addEventListener("click", () => {
        const i = parseInt(btn.getAttribute("data-remove"), 10);
        filesState.splice(i, 1);
        render();
      });
    });
  };

  const addFiles = (fileList) => {
    const incoming = Array.from(fileList);

    // filter PDF saja
    const pdfs = incoming.filter(f => f.type === "application/pdf" || f.name.toLowerCase().endsWith(".pdf"));

    // “simulate upload loading” kecil
    pdfs.forEach(f => filesState.push(f));
    render();
  };

  // click dropzone => open file picker
  dropzone.addEventListener("click", () => fileInput.click());

  // select via input
  fileInput.addEventListener("change", (e) => {
    addFiles(e.target.files);
    // reset supaya pilih file sama masih kebaca
    fileInput.value = "";
  });

  // drag events
  dropzone.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropzone.classList.add("ring-4", "ring-[#9D1535]/10");
  });

  dropzone.addEventListener("dragleave", () => {
    dropzone.classList.remove("ring-4", "ring-[#9D1535]/10");
  });

  dropzone.addEventListener("drop", (e) => {
    e.preventDefault();
    dropzone.classList.remove("ring-4", "ring-[#9D1535]/10");
    addFiles(e.dataTransfer.files);
  });

  render();
}

// ===== DOSEN: EDIT RINGKASAN MATERI (UMUM) =====
const summaryEditBtn = document.getElementById("summaryEditBtn");
const summarySaveBtn = document.getElementById("summarySaveBtn");
const summaryCancelBtn = document.getElementById("summaryCancelBtn");

const summaryText = document.getElementById("summaryText");
const summaryTextarea = document.getElementById("summaryTextarea");
const summaryHint = document.getElementById("summaryHint");

if (summaryEditBtn && summarySaveBtn && summaryCancelBtn && summaryText && summaryTextarea) {
  const STORAGE_KEY = "dosen_summary_umum"; // demo only

  // load from localStorage (biar setelah refresh masih ada)
  const saved = localStorage.getItem(STORAGE_KEY);
  if (saved && saved.trim().length > 0) {
    summaryText.textContent = saved;
    summaryTextarea.value = saved;
  }

  const setEditMode = (isEdit) => {
    if (isEdit) {
      summaryText.classList.add("hidden");
      summaryTextarea.classList.remove("hidden");
      summaryHint?.classList.remove("hidden");

      summaryEditBtn.classList.add("hidden");
      summarySaveBtn.classList.remove("hidden");
      summaryCancelBtn.classList.remove("hidden");

      summaryTextarea.focus();
    } else {
      summaryText.classList.remove("hidden");
      summaryTextarea.classList.add("hidden");
      summaryHint?.classList.add("hidden");

      summaryEditBtn.classList.remove("hidden");
      summarySaveBtn.classList.add("hidden");
      summaryCancelBtn.classList.add("hidden");
    }
  };

  let beforeEdit = summaryTextarea.value;

  summaryEditBtn.addEventListener("click", () => {
    beforeEdit = summaryTextarea.value;
    setEditMode(true);
  });

  summaryCancelBtn.addEventListener("click", () => {
    summaryTextarea.value = beforeEdit;
    setEditMode(false);
  });

  summarySaveBtn.addEventListener("click", () => {
    const val = summaryTextarea.value.trim();

    // update UI
    summaryText.textContent = val.length ? val : "—";

    // demo: simpan ke localStorage
    localStorage.setItem(STORAGE_KEY, val);

    setEditMode(false);
  });
}

// ===== DOSEN: EDIT RINGKASAN PER CLO =====
const cloSummaryEditBtn = document.getElementById("cloSummaryEditBtn");
const cloSummarySaveBtn = document.getElementById("cloSummarySaveBtn");
const cloSummaryCancelBtn = document.getElementById("cloSummaryCancelBtn");

const cloSummaryText = document.getElementById("cloSummaryText");
const cloSummaryTextarea = document.getElementById("cloSummaryTextarea");
const cloSummaryHint = document.getElementById("cloSummaryHint");
const activeCloValue = document.getElementById("activeCloValue");

if (
  cloSummaryEditBtn && cloSummarySaveBtn && cloSummaryCancelBtn &&
  cloSummaryText && cloSummaryTextarea && activeCloValue
) {
  const clo = (activeCloValue.value || "1").toString();
  const STORAGE_KEY = `dosen_summary_clo_${clo}`; // beda per clo

  // load saved
  const saved = localStorage.getItem(STORAGE_KEY);
  if (saved && saved.trim().length > 0) {
    cloSummaryText.textContent = saved;
    cloSummaryTextarea.value = saved;
  }

  const setEditMode = (isEdit) => {
    if (isEdit) {
      cloSummaryText.classList.add("hidden");
      cloSummaryTextarea.classList.remove("hidden");
      cloSummaryHint?.classList.remove("hidden");

      cloSummaryEditBtn.classList.add("hidden");
      cloSummarySaveBtn.classList.remove("hidden");
      cloSummaryCancelBtn.classList.remove("hidden");

      cloSummaryTextarea.focus();
    } else {
      cloSummaryText.classList.remove("hidden");
      cloSummaryTextarea.classList.add("hidden");
      cloSummaryHint?.classList.add("hidden");

      cloSummaryEditBtn.classList.remove("hidden");
      cloSummarySaveBtn.classList.add("hidden");
      cloSummaryCancelBtn.classList.add("hidden");
    }
  };

  let beforeEdit = cloSummaryTextarea.value;

  cloSummaryEditBtn.addEventListener("click", () => {
    beforeEdit = cloSummaryTextarea.value;
    setEditMode(true);
  });

  cloSummaryCancelBtn.addEventListener("click", () => {
    cloSummaryTextarea.value = beforeEdit;
    setEditMode(false);
  });

  cloSummarySaveBtn.addEventListener("click", () => {
    const val = cloSummaryTextarea.value.trim();

    // update UI
    cloSummaryText.textContent = val.length ? val : "—";

    // demo: simpan per CLO
    localStorage.setItem(STORAGE_KEY, val);

    setEditMode(false);
  });
}
