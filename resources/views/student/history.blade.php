{{-- resources/views/student/history.blade.php --}}
@extends('layouts.student')

@section('title', 'Riwayat Percakapan - EduChat')

@section('middle_sidebar')
  @include('student.partials.history-list', [
    'sessions' => $sessions,
    'activeSession' => $activeSession
  ])
@endsection

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
  body { font-family: "Poppins", ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Liberation Sans", sans-serif; }
</style>
@endpush

@section('content')
@php
  $user = auth()->user();
  $nameParts = explode(' ', $user->name);
  $initials = strtoupper(
      substr($nameParts[0], 0, 1) .
      (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : substr($nameParts[0], 1, 1))
  );

  $activeId    = $activeSession['id'] ?? null;
  $activeTitle = $activeSession['title'] ?? 'Riwayat Percakapan';
  $activeTime  = $activeSession['time'] ?? '';
@endphp

<div class="flex gap-6 h-[calc(100vh-5rem)]">

  {{-- ====== CENTER: HISTORY CHAT VIEW ====== --}}
  <div class="flex flex-col flex-1">

    {{-- HEADER --}}
    <header class="flex items-center justify-between bg-white dark:bg-slate-900 rounded-3xl px-6 py-4 mb-4 shadow-sm border border-slate-100 dark:border-slate-800">
      <div class="flex items-center gap-3">
        <a href="{{ route('history') }}"
           class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800">
          <span class="text-lg">&lt;</span>
        </a>

        <div class="flex flex-col">
          <div class="flex items-center gap-2">
            <span class="text-[#B8352E] text-lg">&lt;/&gt;</span>
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-50">
              {{ $activeTitle }}
            </p>
          </div>
          <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ $activeTime }}
          </p>
        </div>
      </div>

      <div class="text-xs text-slate-400">
        @if($activeId) Session #{{ $activeId }} @else Pilih sesi @endif
      </div>
    </header>

    {{-- MESSAGES --}}
    <section class="flex-1 bg-transparent rounded-3xl flex flex-col">
      <div class="flex-1 overflow-y-auto pr-2 space-y-4 pt-2" id="chatMessages">
        @if($activeId === null)
          <div class="h-full flex items-center justify-center">
            <p class="text-sm text-slate-500 dark:text-slate-400">
              Pilih sesi di sebelah kiri untuk melihat percakapan.
            </p>
          </div>
        @else
          @forelse($messages as $m)
            @php
              $role = $m['role'] ?? 'user';
              $content = $m['content'] ?? '';
              $isBot = in_array($role, ['bot', 'assistant'], true);
            @endphp

            @if($isBot)
              <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-full bg-[#B8352E] flex items-center justify-center text-white text-sm">
                  🤖
                </div>
                <div class="max-w-xl rounded-2xl bg-white dark:bg-slate-900 shadow-sm px-4 py-3 text-sm text-slate-800 dark:text-slate-100">
                  {{-- NOTE: konten bot dirender markdown via JS (lihat script bawah) --}}
                  <div class="prose prose-sm max-w-none dark:prose-invert md-content" data-md="1">
                    {{ $content }}
                  </div>
                </div>
              </div>
            @else
              <div class="flex items-start justify-end gap-3">
                <div class="max-w-xl rounded-2xl bg-[#B8352E] text-white shadow-sm px-4 py-3 text-sm">
                  <div class="whitespace-pre-wrap">{{ $content }}</div>
                </div>
                <div class="w-9 h-9 rounded-full bg-slate-300 flex items-center justify-center text-xs font-semibold">
                  {{ $initials }}
                </div>
              </div>
            @endif
          @empty
            <div class="h-full flex items-center justify-center">
              <p class="text-sm text-slate-500 dark:text-slate-400">
                Belum ada pesan pada sesi ini.
              </p>
            </div>
          @endforelse
        @endif
      </div>

      {{-- INPUT (bisa lanjut chat, seperti ChatGPT) --}}
      <div class="mt-4">
        <div class="flex flex-wrap gap-3 mb-3">
          <button type="button"
            class="suggest-msg px-5 py-2 rounded-full text-xs font-medium bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800"
            @if(!$activeId) disabled @endif>
            Jelaskan konsep
          </button>

          <button type="button"
            class="suggest-msg px-5 py-2 rounded-full text-xs font-medium bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800"
            @if(!$activeId) disabled @endif>
            Beri contoh soal & pembahasan
          </button>

          <button type="button"
            class="suggest-msg px-5 py-2 rounded-full text-xs font-medium bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800"
            @if(!$activeId) disabled @endif>
            Apa materi utama dalam topik ini?
          </button>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[32px] shadow-[0_12px_40px_rgba(15,23,42,0.10)] px-5 py-3 flex items-end gap-3">
          <textarea
            id="historyChatInput"
            class="flex-1 resize-none border-none focus:ring-0 focus:outline-none text-sm bg-transparent placeholder:text-slate-400 dark:placeholder:text-slate-500"
            rows="2"
            placeholder="{{ $activeId ? 'Ketik untuk melanjutkan percakapan...' : 'Pilih sesi dulu untuk chat.' }}"
            @if(!$activeId) disabled @endif
          ></textarea>

          <button
            id="historySendBtn"
            type="button"
            class="w-10 h-10 rounded-full bg-[#B8352E] flex items-center justify-center text-white shadow-md hover:bg-[#8f251f] disabled:opacity-60"
            @if(!$activeId) disabled @endif>
            <span class="text-sm">↑</span>
          </button>
        </div>

        <p id="historySendingHint" class="text-xs text-slate-500 dark:text-slate-400 mt-2 hidden">
          Mengirim...
        </p>
      </div>

    </section>
  </div>

</div>
@endsection

@push('scripts')
{{-- Markdown renderer (CDN cepat). Kalau kamu mau via Vite juga bisa. --}}
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.8/dist/purify.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const activeId = @json($activeId);
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

  const chatMessages = document.getElementById("chatMessages");
  const input = document.getElementById("historyChatInput");
  const btn = document.getElementById("historySendBtn");
  const hint = document.getElementById("historySendingHint");

  // Render markdown untuk semua elemen bot yang sudah ada (server render)
  document.querySelectorAll(".md-content[data-md='1']").forEach(el => {
    const md = el.textContent ?? "";
    const html = window.marked ? window.marked.parse(md) : md;
    el.innerHTML = window.DOMPurify ? window.DOMPurify.sanitize(html) : html;
  });

  const setSending = (v) => {
    if (hint) hint.classList.toggle("hidden", !v);
    if (btn) btn.disabled = v;
  };

  const escapeHtml = (str) => String(str).replace(/[&<>"']/g, (m) => ({
    "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"
  }[m]));

  const appendUser = (text) => {
    chatMessages.insertAdjacentHTML("beforeend", `
      <div class="flex items-start justify-end gap-3">
        <div class="max-w-xl rounded-2xl bg-[#B8352E] text-white shadow-sm px-4 py-3 text-sm">
          <div class="whitespace-pre-wrap">${escapeHtml(text)}</div>
        </div>
        <div class="w-9 h-9 rounded-full bg-slate-300 flex items-center justify-center text-xs font-semibold">
          {{ $initials }}
        </div>
      </div>
    `);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  };

  const appendBotMarkdown = (mdText) => {
    const htmlRaw = window.marked ? window.marked.parse(mdText ?? "") : (mdText ?? "");
    const html = window.DOMPurify ? window.DOMPurify.sanitize(htmlRaw) : htmlRaw;

    chatMessages.insertAdjacentHTML("beforeend", `
      <div class="flex items-start gap-3">
        <div class="w-9 h-9 rounded-full bg-[#B8352E] flex items-center justify-center text-white text-sm">🤖</div>
        <div class="max-w-xl rounded-2xl bg-white dark:bg-slate-900 shadow-sm px-4 py-3 text-sm text-slate-800 dark:text-slate-100">
          <div class="prose prose-sm max-w-none dark:prose-invert">
            ${html}
          </div>
        </div>
      </div>
    `);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  };

  async function send(messageOverride) {
    if (!activeId) return;
    if (!csrf) {
      appendBotMarkdown("CSRF token tidak ditemukan. Pastikan layout punya meta csrf-token.");
      return;
    }

    const msg = (messageOverride ?? input.value).trim();
    if (!msg) return;

    appendUser(msg);
    input.value = "";
    setSending(true);

    try {
      const res = await fetch(`/history/${activeId}/ask`, {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "X-CSRF-TOKEN": csrf,
        },
        body: JSON.stringify({ message: msg })
      });

      const data = await res.json().catch(() => null);

      if (!res.ok) {
        appendBotMarkdown(data?.message || "Terjadi error.");
        console.error("History ask error:", res.status, data);
        return;
      }

      appendBotMarkdown(data?.reply || "Maaf, aku belum bisa menjawab.");
    } catch (e) {
      console.error(e);
      appendBotMarkdown("Gagal terhubung ke server.");
    } finally {
      setSending(false);
    }
  }

  btn?.addEventListener("click", () => send());

  input?.addEventListener("keydown", (e) => {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      send();
    }
  });

  document.querySelectorAll(".suggest-msg").forEach(b => {
    b.addEventListener("click", () => send(b.innerText));
  });
});
</script>
@endpush
