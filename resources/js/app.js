// app.js (FINAL - GLOBAL DARKMODE DELEGATION + SAFE GUARDS + DROPZONE SINGLE)

// =========================
// DARK MODE (GLOBAL + DELEGATION)
// - Works on admin + student
// - Works even if DOM is replaced / rendered later (Turbo/Livewire/partials)
// =========================
(() => {
  const html = document.documentElement;

  // apply saved
  const saved = localStorage.getItem("theme");
  if (saved === "dark") html.classList.add("dark");
  if (saved === "light") html.classList.remove("dark");

  document.addEventListener("click", (e) => {
    if (!e.target.closest("#darkModeToggle")) return;

    const willBeDark = !html.classList.contains("dark");

    // ✅ SET EXPLICIT (bukan toggle)
    if (willBeDark) {
      html.classList.add("dark");
      localStorage.setItem("theme", "dark");
      console.log("✅ THEME NOW: dark");
    } else {
      html.classList.remove("dark");
      localStorage.setItem("theme", "light");
      console.log("✅ THEME NOW: light");
    }
  });
})();



// =========================
// DOM READY FEATURES (safe to bind after DOM is ready)
// =========================
document.addEventListener("DOMContentLoaded", () => {
  console.log("APP.JS LOADED");

  // =========================
  // TOGGLE RIGHT SIDEBAR (CLO INFO)
  // =========================
  const infoToggleBtn = document.getElementById("infoToggleBtn");
  const infoPanel = document.getElementById("cloInfoPanel");

  if (infoToggleBtn && infoPanel) {
    infoToggleBtn.addEventListener("click", () => {
      infoPanel.classList.toggle("hidden");
    });
  }

  // =========================
  // PROFILE POPUP
  // =========================
  const profileBtn = document.getElementById("profileBtn");
  const profilePopup = document.getElementById("profilePopup");
  const profileOverlay = document.getElementById("profileOverlay");

  if (profileBtn && profilePopup && profileOverlay) {
    const open = () => {
      profilePopup.classList.remove("hidden");
      profileOverlay.classList.remove("hidden");
      profileOverlay.classList.remove("pointer-events-none");
    };

    const close = () => {
      profilePopup.classList.add("hidden");
      profileOverlay.classList.add("hidden");
      profileOverlay.classList.add("pointer-events-none");
    };

    const isOpen = () => !profilePopup.classList.contains("hidden");

    // Make overlay safe by default
    profileOverlay.classList.add("pointer-events-none");

    profileBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      isOpen() ? close() : open();
    });

    profileOverlay.addEventListener("click", close);

    document.addEventListener("click", (e) => {
      if (!isOpen()) return;
      if (profilePopup.contains(e.target) || profileBtn.contains(e.target)) return;
      close();
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") close();
    });
  }

  // =========================
  // DROPZONE (SINGLE SOURCE OF TRUTH)
  // Works for materi-create page AND any other page with these ids
  // =========================
  const dropzone = document.getElementById("dropzone");
  const fileInput = document.getElementById("fileInput");
  const fileGrid = document.getElementById("fileGrid");
  const emptyHint = document.getElementById("emptyHint");

  if (dropzone && fileInput && fileGrid && emptyHint) {
    const filesState = [];

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

      fileGrid.querySelectorAll("[data-remove]").forEach((btn) => {
        btn.addEventListener("click", () => {
          const i = parseInt(btn.getAttribute("data-remove"), 10);
          filesState.splice(i, 1);
          render();
        });
      });

      // expose for other scripts if needed:
      window.__FILES_STATE__ = filesState;
    };

    const addFiles = (fileList) => {
      const incoming = Array.from(fileList || []);
      const pdfs = incoming.filter(
        (f) => f.type === "application/pdf" || f.name.toLowerCase().endsWith(".pdf")
      );

      if (!pdfs.length) return;

      pdfs.forEach((f) => filesState.push(f));
      render();
    };

    dropzone.addEventListener("click", () => fileInput.click());

    fileInput.addEventListener("change", (e) => {
      addFiles(e.target.files);
      fileInput.value = "";
    });

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

  // =========================
  // DOSEN: EDIT RINGKASAN MATERI (UMUM)
  // =========================
  const summaryEditBtn = document.getElementById("summaryEditBtn");
  const summarySaveBtn = document.getElementById("summarySaveBtn");
  const summaryCancelBtn = document.getElementById("summaryCancelBtn");
  const summaryText = document.getElementById("summaryText");
  const summaryTextarea = document.getElementById("summaryTextarea");
  const summaryHint = document.getElementById("summaryHint");

  if (
    summaryEditBtn &&
    summarySaveBtn &&
    summaryCancelBtn &&
    summaryText &&
    summaryTextarea
  ) {
    const STORAGE_KEY = "dosen_summary_umum";
    const savedSum = localStorage.getItem(STORAGE_KEY);

    if (savedSum && savedSum.trim().length > 0) {
      summaryText.textContent = savedSum;
      summaryTextarea.value = savedSum;
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
      summaryText.textContent = val.length ? val : "—";
      localStorage.setItem(STORAGE_KEY, val);
      setEditMode(false);
    });
  }

  // =========================
  // DOSEN: EDIT RINGKASAN PER CLO
  // =========================
  const cloSummaryEditBtn = document.getElementById("cloSummaryEditBtn");
  const cloSummarySaveBtn = document.getElementById("cloSummarySaveBtn");
  const cloSummaryCancelBtn = document.getElementById("cloSummaryCancelBtn");
  const cloSummaryText = document.getElementById("cloSummaryText");
  const cloSummaryTextarea = document.getElementById("cloSummaryTextarea");
  const cloSummaryHint = document.getElementById("cloSummaryHint");
  const activeCloValue = document.getElementById("activeCloValue");

  if (
    cloSummaryEditBtn &&
    cloSummarySaveBtn &&
    cloSummaryCancelBtn &&
    cloSummaryText &&
    cloSummaryTextarea &&
    activeCloValue
  ) {
    const clo = (activeCloValue.value || "1").toString();
    const STORAGE_KEY = `dosen_summary_clo_${clo}`;

    const savedClo = localStorage.getItem(STORAGE_KEY);
    if (savedClo && savedClo.trim().length > 0) {
      cloSummaryText.textContent = savedClo;
      cloSummaryTextarea.value = savedClo;
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
      cloSummaryText.textContent = val.length ? val : "—";
      localStorage.setItem(STORAGE_KEY, val);
      setEditMode(false);
    });
  }
});
