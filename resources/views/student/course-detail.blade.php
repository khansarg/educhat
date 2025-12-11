<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Strategi Algoritma - CLO 1 | EduChat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #B8352E;
            --primary-light: #FDF2F2;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-500: #6B7280;
            --gray-700: #374151;
            --gray-900: #111827;
            --bg-chat: #F9FAFB;
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #FFFFFF;
            color: var(--gray-900);
            height: 100vh;
            display: grid;
            grid-template-columns: 340px 1fr;
            grid-template-rows: 64px 1fr;
            grid-template-areas: 
                "sidebar header"
                "sidebar main";
        }

        /* ===== SIDEBAR (Learning Path) ===== */
        .sidebar {
            grid-area: sidebar;
            background: white;
            border-right: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--gray-200);
        }
        .sidebar-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 4px;
        }
        .sidebar-course {
            font-size: 18px;
            font-weight: 600;
        }
        .sidebar-course-code {
            font-size: 12px;
            color: var(--gray-500);
        }

        .learning-path {
            flex: 1;
            overflow-y: auto;
            padding: 12px 0;
        }
        .clo-group {
            margin-bottom: 8px;
        }
        .clo-group-title {
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
            background: var(--gray-100);
        }
        .clo-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .clo-item:hover { background: var(--gray-100); }
        .clo-item.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            position: relative;
        }
        .clo-item.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary);
        }
        .clo-item-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: bold;
        }
        .clo-item.active .clo-item-icon {
            background: var(--primary);
            color: white;
        }
        .clo-item-label {
            font-size: 14px;
        }

        /* ===== HEADER ===== */
        .header {
            grid-area: header;
            border-bottom: 1px solid var(--gray-200);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo {
            font-size: 20px;
            font-weight: bold;
            color: var(--primary);
        }

        /* ===== MAIN CHAT AREA ===== */
        .main {
            grid-area: main;
            background: var(--bg-chat);
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            padding: 16px 24px;
            background: white;
            border-bottom: 1px solid var(--gray-200);
            font-weight: 600;
            font-size: 15px;
        }
        .chat-messages {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .bubble {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            line-height: 1.5;
            font-size: 14px;
        }
        .bubble.bot {
            align-self: flex-start;
            background: white;
            border-bottom-left-radius: 4px;
        }
        .bubble.user {
            align-self: flex-end;
            background: var(--primary);
            color: white;
            border-bottom-right-radius: 4px;
        }
        .chat-input-area {
            padding: 16px 24px;
            background: white;
            border-top: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .suggest-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .suggest-btn {
            padding: 8px 16px;
            background: var(--gray-100);
            border: 1px solid var(--gray-300);
            border-radius: 999px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .suggest-btn:hover { background: var(--gray-200); }

        .input-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--gray-100);
            border-radius: 999px;
            padding: 8px 16px;
        }
        .input-wrapper input {
            flex: 1;
            border: none;
            background: transparent;
            outline: none;
            font-size: 14px;
        }
        .send-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-title">Learning Path</div>
            <div class="sidebar-course">Strategi Algoritma</div>
            <div class="sidebar-course-code">CLO 1</div>
        </div>

        <nav class="learning-path">
            <div class="clo-group">
                <div class="clo-group-title">Algoritma Pemrograman</div>
                <div class="clo-item">
                    <div class="clo-item-icon">✓</div>
                    <div class="clo-item-label">Algoritma Pemrograman (lanjutan)</div>
                </div>
            </div>

            <div class="clo-group">
                <div class="clo-group-title">Analisis Kompleksitas Algoritma</div>
                <div class="clo-item">
                    <div class="clo-item-icon">✓</div>
                    <div class="clo-item-label">Analisis Kompleksitas Algoritma</div>
                </div>
            </div>

            <div class="clo-group">
                <div class="clo-group-title">Strategi Algoritma</div>
                <div class="clo-item active">
                    <div class="clo-item-icon">1</div>
                    <div class="clo-item-label">CLO 1</div>
                </div>
                <div class="clo-item">
                    <div class="clo-item-icon">2</div>
                    <div class="clo-item-label">CLO 2</div>
                </div>
            </div>
        </nav>
    </aside>

    <!-- HEADER -->
    <header class="header">
        <div class="header-left">
            <div class="logo">&lt;/&gt;</div>
            <div>EduChat</div>
        </div>
        <div>
            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Aqiq" width="36" style="border-radius:50%">
        </div>
    </header>

    <!-- MAIN CHAT -->
    <main class="main">
        <div class="chat-header">CLO 1 – Brute Force & Divide and Conquer</div>

        <div class="chat-messages">
            <div class="bubble bot">
                Halo Aqiq! Ada yang bisa saya bantu?
            </div>

            <div class="bubble user">
                Halo Aqiq! Ada yang bisa saya bantu?
            </div>

            <div class="bubble bot">
                Halo Aqiq! Ada yang bisa saya bantu?
            </div>
        </div>

        <div class="chat-input-area">
            <div class="suggest-buttons">
                <button class="suggest-btn">Suggest satu</button>
                <button class="suggest-btn">Suggest satu</button>
                <button class="suggest-btn">Suggest satu</button>
            </div>

            <div class="input-wrapper">
                <input type="text" placeholder="Tanya pertanyaan di sini...">
                <button class="send-btn">↑</button>
            </div>
        </div>
    </main>
</body>
</html>