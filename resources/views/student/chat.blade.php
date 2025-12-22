{{-- resources/views/student/chat.blade.php --}}
@extends('layouts.student')

@section('title', 'Percakapan Pembelajaran - EduChat')

@section('middle_sidebar')
    @include('student.partials.course-list', [
        'courses' => $courses,
        'currentCourse' => $course->id,
        'currentClo' => $clo->id
    ])
@endsection

@section('content')
@php
    $user = auth()->user();
    $nameParts = explode(' ', $user->name);
    $initials = strtoupper(
        substr($nameParts[0], 0, 1) .
        (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : substr($nameParts[0], 1, 1))
    );
@endphp

<div class="flex gap-6 h-[calc(100vh-5rem)]">

    {{-- ====== LEFT: CHAT AREA ====== --}}
    <div class="flex flex-col flex-1">

        {{-- HEADER --}}
        <header
            class="flex items-center justify-between bg-white dark:bg-slate-900 rounded-3xl px-6 py-4 mb-4 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <a href="{{ route('course.show', $course->id) }}"
                   class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800">
                    <span class="text-lg">&lt;</span>
                </a>

                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        <span class="text-[#B8352E] text-lg">&lt;/&gt;</span>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                            {{ $course->name }}
                        </p>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ $clo->name }}
                    </p>
                </div>
            </div>

            {{-- toggle right info --}}
            <button
                id="infoToggleBtn"
                class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-800"
                type="button">
                <span class="text-sm">📘</span>
            </button>
        </header>

        {{-- CHAT AREA --}}
        <section class="flex-1 bg-transparent rounded-3xl flex flex-col">

            {{-- MESSAGES --}}
            <div id="chatMessages" class="flex-1 overflow-y-auto pr-2 space-y-4 pt-2">

                {{-- greeting bot --}}
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#B8352E] flex items-center justify-center text-white text-sm">
                        🤖
                    </div>
                    <div class="max-w-xl rounded-2xl bg-white dark:bg-slate-900 shadow-sm px-4 py-3 text-sm text-slate-800 dark:text-slate-100">
                        <p><span class="font-semibold">EduChat:</span> Halo {{ explode(' ', $user->name)[0] }}! Ada yang bisa saya bantu?</p>
                    </div>
                </div>

            </div>

            {{-- INPUT --}}
            <div class="mt-4">
                <div class="flex flex-wrap gap-3 mb-3">
                    <button type="button"
                        class="suggest-msg px-5 py-2 rounded-full text-xs font-medium bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800">
                        Jelaskan konsep {{ $clo->name }}
                    </button>

                    <button type="button"
                        class="suggest-msg px-5 py-2 rounded-full text-xs font-medium bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800">
                        Beri contoh soal & pembahasan
                    </button>

                    <button type="button"
                        class="suggest-msg px-5 py-2 rounded-full text-xs font-medium bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800">
                        Apa materi utama dalam {{ $clo->name }}?
                    </button>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-[32px] shadow-[0_12px_40px_rgba(15,23,42,0.10)] px-5 py-3 flex items-end gap-3">
                    <textarea
                        id="chatInput"
                        class="flex-1 resize-none border-none focus:ring-0 focus:outline-none text-sm bg-transparent placeholder:text-slate-400 dark:placeholder:text-slate-500"
                        rows="2"
                        placeholder="Tanya pertanyaanmu disini..."></textarea>

                    <button
                        id="sendBtn"
                        type="button"
                        class="w-10 h-10 rounded-full bg-[#B8352E] flex items-center justify-center text-white shadow-md hover:bg-[#8f251f]">
                        <span class="text-sm">↑</span>
                    </button>
                </div>

                <p id="sendingHint" class="text-xs text-slate-500 dark:text-slate-400 mt-2 hidden">
                    Mengirim...
                </p>
            </div>
        </section>
    </div>

    {{-- ====== RIGHT: INFO PANEL ====== --}}
    {{-- ====== RIGHT: INFO PANEL ====== --}}
<aside
  id="cloInfoPanel"
  class="w-80 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 hidden">

  <h2 class="text-sm font-semibold text-[#B8352E] mb-3">
    {{ $course->name }}
  </h2>

  <div class="mb-4">
    <p class="text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">CLO:</p>
    <p class="text-sm font-semibold text-slate-900 dark:text-slate-50">
      {{ $clo->name ?? $clo->title ?? 'CLO' }}
    </p>
  </div>

  {{-- CLO Summary/Description --}}
  <div class="text-xs leading-relaxed text-slate-700 dark:text-slate-300 space-y-3">
    <div class="rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 p-4">
      <p class="text-xs font-semibold text-slate-900 dark:text-slate-100 mb-2">Ringkasan CLO</p>

      @php
        $cloSummary = $clo->summary ?? $clo->description ?? null;
      @endphp

      @if($cloSummary)
        <p>{{ $cloSummary }}</p>
      @else
        <p class="text-slate-500 dark:text-slate-400">Belum ada ringkasan CLO.</p>
      @endif
    </div>

    {{-- Materials list --}}
    <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-700 p-4">
      <p class="text-xs font-semibold text-slate-900 dark:text-slate-100 mb-3">Materi & PDF</p>

      @if($clo->materials && $clo->materials->count())
        <div class="space-y-3">
          @foreach($clo->materials as $m)
            <div class="border-b border-slate-100 dark:border-slate-800 pb-3 last:border-b-0 last:pb-0">
              <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                {{ $m->title }}
              </p>

              @if($m->files && $m->files->count())
                <ul class="mt-2 space-y-1">
                  @foreach($m->files as $f)
                    <li>
                      <a href="{{ $f->download_url }}"
                         target="_blank"
                         class="text-xs text-blue-600 dark:text-blue-400 hover:underline break-words">
                        📄 {{ $f->original_name ?? basename($f->pdf_path ?? 'file.pdf') }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              @else
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Belum ada file PDF.</p>
              @endif
            </div>
          @endforeach
        </div>
      @else
        <p class="text-xs text-slate-500 dark:text-slate-400">Belum ada materi pada CLO ini.</p>
      @endif
    </div>

    
  </div>
</aside>


</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
  const chatInput = document.getElementById("chatInput");
  const sendBtn = document.getElementById("sendBtn");
  const chatMessages = document.getElementById("chatMessages");
  const sendingHint = document.getElementById("sendingHint");

  const infoToggleBtn = document.getElementById("infoToggleBtn");
  const infoPanel = document.getElementById("cloInfoPanel");

  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

  const courseId = @json($course->id);
  const cloId = @json($clo->id);

    let sessionId = null;

  const setSending = (v) => {
    if (!sendingHint) return;
    sendingHint.classList.toggle("hidden", !v);
    if (sendBtn) sendBtn.disabled = v;
  };

  const escapeHtml = (str) => String(str).replace(/[&<>"']/g, (m) => ({
    "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"
  }[m]));

  const appendUser = (text) => {
    chatMessages.insertAdjacentHTML("beforeend", `
      <div class="flex items-start justify-end gap-3">
        <div class="max-w-xl rounded-2xl bg-[#B8352E] text-white shadow-sm px-4 py-3 text-sm">
          <p>${escapeHtml(text)}</p>
        </div>
        <div class="w-9 h-9 rounded-full bg-slate-300 flex items-center justify-center text-xs font-semibold">
          U
        </div>
      </div>
    `);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  };

  const renderMarkdown = (md) => {
  const raw = window.marked ? window.marked.parse(md ?? "") : (md ?? "");
  return window.DOMPurify ? window.DOMPurify.sanitize(raw) : raw;
};

const appendBot = (mdText) => {
  const html = renderMarkdown(mdText);

  chatMessages.insertAdjacentHTML("beforeend", `
    <div class="flex items-start gap-3">
      <div class="w-9 h-9 rounded-full bg-[#B8352E] flex items-center justify-center text-white text-sm">
        🤖
      </div>
      <div class="max-w-xl rounded-2xl bg-white dark:bg-slate-900 shadow-sm px-4 py-3 text-sm text-slate-800 dark:text-slate-100">
        <div class="prose prose-sm max-w-none dark:prose-invert">
          ${html}
        </div>
      </div>
    </div>
  `);

  chatMessages.scrollTop = chatMessages.scrollHeight;
};


  async function sendMessage(text) {
    if (!csrf) {
      appendBot("CSRF token tidak ditemukan. Pastikan layout punya meta csrf-token.");
      return;
    }

    const msg = (text ?? chatInput.value).trim();
    if (!msg) return;

    appendUser(msg);
    chatInput.value = "";
    setSending(true);

    try {
      const res = await fetch(`/chat/${courseId}/clo/${cloId}/ask`, {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "X-CSRF-TOKEN": csrf,
        },
        body: JSON.stringify({ message: msg,
    session_id: sessionId })
      });

      const data = await res.json().catch(() => null);
      if (data?.session_id) {
        sessionId = data.session_id;
    }
      if (!res.ok) {
        const err = data?.message || "Terjadi error saat memproses chat.";
        appendBot(err);
        console.error("Chat error:", res.status, data);
        return;
      }

      appendBot(data?.reply || "Maaf, aku belum bisa menjawab.");
    } catch (e) {
      console.error(e);
      appendBot("Gagal terhubung ke server.");
    } finally {
      setSending(false);
    }
  }

  if (sendBtn) {
    sendBtn.addEventListener("click", () => sendMessage());
  }

  if (chatInput) {
    chatInput.addEventListener("keydown", (e) => {
      if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
      }
    });
  }

  // suggest buttons
  document.querySelectorAll(".suggest-msg").forEach(btn => {
    btn.addEventListener("click", () => sendMessage(btn.innerText));
  });

  // toggle right panel
  // toggle right panel
if (infoToggleBtn && infoPanel) {
  infoToggleBtn.addEventListener("click", () => {
    const willOpen = infoPanel.classList.contains("hidden");

    if (willOpen) {
      infoPanel.classList.remove("hidden");
      infoPanel.classList.add("flex", "flex-col");
    } else {
      infoPanel.classList.add("hidden");
      infoPanel.classList.remove("flex", "flex-col");
    }
  });
}

});
</script>
@endpush
