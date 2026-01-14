<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Study Planner (Student Edition)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap');
        
        body { 
            font-family: 'Google Sans', 'Kanit', sans-serif; 
            /* พื้นหลังแบบ Dot Pattern นุ่มๆ */
            background-color: #f8fafc;
            background-image: radial-gradient(#e0e7ff 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* --- Animations --- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes popIn {
            0% { transform: scale(0.8); opacity: 0; }
            80% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); }
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }

        .animate-fade-in { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-pop-in { animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
        .hover-float:hover { animation: float 2s ease-in-out infinite; }

        /* --- Custom Styles --- */
        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }

        .input-modern {
            transition: all 0.3s ease;
            border: 2px solid transparent;
            background-color: #f1f5f9;
        }
        .input-modern:focus {
            background-color: #ffffff;
            border-color: #818cf8;
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(129, 140, 248, 0.2);
        }

        .time-slot { height: 60px; border-bottom: 1px dashed #cbd5e1; }
        
        /* Scrollbar น่ารักๆ */
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 5px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; border: 2px solid #f1f5f9; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="h-screen overflow-hidden flex flex-col text-slate-700">

    <!-- Header -->
    <header class="glass-header px-6 py-3 flex justify-between items-center z-30 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="bg-gradient-to-tr from-indigo-500 to-purple-500 text-white p-2.5 rounded-2xl shadow-lg transform hover:rotate-12 transition-transform duration-300">
                <i class="fas fa-calendar-check text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
                    Smart Planner
                </h1>
                <p class="text-xs text-slate-500 font-medium tracking-wide">จัดตารางเรียนแบบคนเก๋ๆ ✨</p>
            </div>
        </div>
        <button onclick="resetData()" class="group bg-rose-50 hover:bg-rose-100 text-rose-500 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 shadow-sm hover:shadow-md flex items-center gap-2">
            <i class="fas fa-trash-alt group-hover:rotate-12 transition-transform"></i> 
            <span>ล้างข้อมูล</span>
        </button>
    </header>

    <div class="flex flex-1 overflow-hidden relative">
        
        <!-- Sidebar -->
        <aside class="w-full md:w-80 glass-panel z-20 overflow-y-auto p-6 flex flex-col gap-6 m-4 mr-0 rounded-3xl animate-fade-in shadow-xl border-r-0">
            <div>
                <h2 class="text-lg font-bold mb-5 text-slate-700 flex items-center gap-2">
                    <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg text-sm"><i class="fas fa-plus"></i></span>
                    เพิ่มวิชาใหม่
                </h2>
                <form id="classForm" class="space-y-5">
                    <!-- Subject Name -->
                    <div class="group">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 group-focus-within:text-indigo-500 transition-colors">ชื่อวิชา</label>
                        <input type="text" id="subjectName" required class="input-modern w-full px-4 py-2.5 rounded-xl outline-none text-slate-700 font-medium" placeholder="เช่น คณิตศาสตร์ 📐">
                    </div>

                    <!-- Teacher Name -->
                    <div class="group">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 group-focus-within:text-indigo-500 transition-colors">ครูผู้สอน</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </span>
                            <input type="text" id="teacherName" class="input-modern w-full pl-10 px-4 py-2.5 rounded-xl outline-none text-slate-700" placeholder="อ.ใจดี">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="group">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">วันเรียน</label>
                            <div class="relative">
                                <select id="dayOfWeek" class="input-modern w-full px-4 py-2.5 rounded-xl outline-none appearance-none cursor-pointer">
                                    <option value="Monday">จันทร์ 💛</option>
                                    <option value="Tuesday">อังคาร 🌸</option>
                                    <option value="Wednesday">พุธ 💚</option>
                                    <option value="Thursday">พฤหัส 🧡</option>
                                    <option value="Friday">ศุกร์ 💙</option>
                                    <option value="Saturday">เสาร์ 💜</option>
                                    <option value="Sunday">อาทิตย์ ❤️</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">ธีมสี</label>
                            <div class="flex items-center gap-2 h-[46px] bg-slate-50 rounded-xl px-2 border border-slate-200">
                                <input type="color" id="colorTag" value="#818cf8" class="w-8 h-8 rounded-full cursor-pointer border-none bg-transparent p-0">
                                <span class="text-xs text-slate-400">เลือกสี</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">เริ่ม</label>
                            <input type="time" id="startTime" required class="input-modern w-full px-2 py-2.5 rounded-xl outline-none text-center font-mono text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">ถึง</label>
                            <input type="time" id="endTime" required class="input-modern w-full px-2 py-2.5 rounded-xl outline-none text-center font-mono text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">ห้องเรียน</label>
                        <input type="text" id="room" class="input-modern w-full px-4 py-2.5 rounded-xl outline-none" placeholder="ระบุห้องเรียน 📍">
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-200 transform hover:-translate-y-1 hover:shadow-xl transition-all duration-300 flex justify-center items-center gap-2">
                        <i class="fas fa-save"></i> บันทึกตาราง
                    </button>
                </form>
            </div>

            <!-- Stats -->
            <div class="bg-gradient-to-br from-white to-indigo-50 p-5 rounded-2xl shadow-sm border border-indigo-100 mt-auto">
                <h3 class="text-sm font-bold text-indigo-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-chart-pie"></i> สรุปการเรียน
                </h3>
                <div class="flex justify-between items-center mb-2 p-2 bg-white rounded-lg shadow-sm">
                    <span class="text-xs font-medium text-slate-500">จำนวนวิชา</span>
                    <span id="totalClasses" class="font-bold text-indigo-600 text-lg">0</span>
                </div>
                <div class="flex justify-between items-center p-2 bg-white rounded-lg shadow-sm">
                    <span class="text-xs font-medium text-slate-500">ชั่วโมงรวม</span>
                    <span id="totalHours" class="font-bold text-purple-600 text-lg">0 ชม.</span>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto relative p-4" id="timetableContainer">
            <div class="min-w-[800px] bg-white rounded-[30px] shadow-xl border border-white/50 overflow-hidden animate-fade-in" style="animation-delay: 0.2s;">
                <!-- Days Header -->
                <div class="grid grid-cols-8 bg-slate-50/80 backdrop-blur sticky top-0 z-20 border-b border-slate-200">
                    <div class="p-4 text-center font-bold text-slate-400 text-sm border-r border-slate-100 flex items-center justify-center bg-slate-50">
                        <i class="far fa-clock mr-1"></i> เวลา
                    </div>
                    <div class="p-3 text-center font-bold text-slate-600"><span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">จันทร์</span></div>
                    <div class="p-3 text-center font-bold text-slate-600"><span class="bg-pink-100 text-pink-700 px-3 py-1 rounded-full text-sm">อังคาร</span></div>
                    <div class="p-3 text-center font-bold text-slate-600"><span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">พุธ</span></div>
                    <div class="p-3 text-center font-bold text-slate-600"><span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-sm">พฤหัส</span></div>
                    <div class="p-3 text-center font-bold text-slate-600"><span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">ศุกร์</span></div>
                    <div class="p-3 text-center font-bold text-slate-600"><span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">เสาร์</span></div>
                    <div class="p-3 text-center font-bold text-slate-600"><span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">อาทิตย์</span></div>
                </div>

                <!-- Schedule Grid -->
                <div class="relative bg-white" id="scheduleGrid" style="height: 960px;">
                    <!-- Time Labels -->
                    <div class="absolute left-0 top-0 bottom-0 w-[12.5%] border-r border-dashed border-slate-200 bg-slate-50/30 z-10" id="timeLabels"></div>
                    
                    <!-- Vertical Lines -->
                    <div class="absolute left-[12.5%] right-0 top-0 bottom-0 grid grid-cols-7 pointer-events-none">
                        <div class="border-r border-dashed border-slate-100 h-full"></div>
                        <div class="border-r border-dashed border-slate-100 h-full"></div>
                        <div class="border-r border-dashed border-slate-100 h-full"></div>
                        <div class="border-r border-dashed border-slate-100 h-full"></div>
                        <div class="border-r border-dashed border-slate-100 h-full"></div>
                        <div class="border-r border-dashed border-slate-100 h-full bg-purple-50/10"></div>
                        <div class="h-full bg-red-50/10"></div>
                    </div>
                    
                    <!-- Horizontal Lines -->
                    <div id="horizontalLines" class="absolute left-[12.5%] right-0 top-0 bottom-0 pointer-events-none"></div>
                    
                    <!-- Classes Container -->
                    <div id="classesLayer" class="absolute left-[12.5%] right-0 top-0 bottom-0 z-10"></div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal -->
    <div id="infoModal" class="hidden fixed inset-0 bg-slate-900/40 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity duration-300">
        <div class="glass-panel rounded-3xl shadow-2xl p-8 w-[400px] transform transition-all scale-100 animate-pop-in border border-white">
            <div class="flex justify-between items-start mb-6">
                <div class="bg-indigo-100 p-3 rounded-2xl text-indigo-600">
                   <i class="fas fa-book-open text-2xl"></i>
                </div>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 hover:rotate-90 transition-transform bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <h3 id="modalTitle" class="text-2xl font-bold text-slate-800 mb-1"></h3>
            <p class="text-sm text-slate-500 mb-6 font-medium">รายละเอียดวิชา</p>

            <div class="space-y-4 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center shadow-sm"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">ผู้สอน</p>
                        <p id="modalTeacher" class="text-slate-700 font-medium">-</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-500 flex items-center justify-center shadow-sm"><i class="far fa-clock"></i></div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">เวลาเรียน</p>
                        <p id="modalTime" class="text-slate-700 font-medium">-</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-pink-100 text-pink-500 flex items-center justify-center shadow-sm"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">สถานที่</p>
                        <p id="modalRoom" class="text-slate-700 font-medium">-</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end gap-3">
                <button onclick="deleteClassFromModal()" class="flex-1 bg-rose-50 hover:bg-rose-500 hover:text-white text-rose-500 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-300">
                    <i class="fas fa-trash-alt mr-2"></i> ลบวิชา
                </button>
            </div>
        </div>
    </div>

    <script>
        const START_HOUR = 7;
        const END_HOUR = 21;
        const PIXELS_PER_HOUR = 60;
        let scheduleData = [];
        let selectedClassId = null;

        const dayMap = { 'Monday': 0, 'Tuesday': 1, 'Wednesday': 2, 'Thursday': 3, 'Friday': 4, 'Saturday': 5, 'Sunday': 6 };
        const dayNamesTh = { 'Monday': 'จันทร์', 'Tuesday': 'อังคาร', 'Wednesday': 'พุธ', 'Thursday': 'พฤหัสบดี', 'Friday': 'ศุกร์', 'Saturday': 'เสาร์', 'Sunday': 'อาทิตย์' };

        document.addEventListener('DOMContentLoaded', () => {
            renderGridBackground();
            loadData();
        });

        function renderGridBackground() {
            const timeCol = document.getElementById('timeLabels');
            const linesCol = document.getElementById('horizontalLines');
            const totalHours = END_HOUR - START_HOUR;

            document.getElementById('scheduleGrid').style.height = `${totalHours * PIXELS_PER_HOUR}px`;

            for (let h = START_HOUR; h <= END_HOUR; h++) {
                const label = document.createElement('div');
                label.className = 'text-xs text-slate-400 text-right pr-4 pt-1 -mt-2.5 font-mono opacity-60';
                label.style.height = `${PIXELS_PER_HOUR}px`;
                label.innerText = `${h.toString().padStart(2, '0')}:00`;
                timeCol.appendChild(label);

                if (h < END_HOUR) {
                    const line = document.createElement('div');
                    line.className = 'border-b border-dashed border-slate-100 w-full absolute';
                    line.style.top = `${(h - START_HOUR) * PIXELS_PER_HOUR}px`;
                    linesCol.appendChild(line);
                }
            }
        }

        async function loadData() {
            try {
                const response = await fetch('api.php');
                scheduleData = await response.json();
                renderClasses();
                updateStats();
            } catch (error) {
                console.error("Error loading data:", error);
                if(window.location.protocol === 'file:') {
                    alert("กรุณารันผ่าน Localhost เพื่อเชื่อมต่อฐานข้อมูล");
                }
            }
        }

        function renderClasses() {
            const container = document.getElementById('classesLayer');
            container.innerHTML = '';

            scheduleData.forEach((cls, index) => {
                const dayIndex = dayMap[cls.day];
                const startDec = timeToDecimal(cls.start);
                const endDec = timeToDecimal(cls.end);
                const duration = endDec - startDec;

                const block = document.createElement('div');
                // เพิ่ม Animation pop-in ให้แต่ละ Block โดยหน่วงเวลาเล็กน้อยตามลำดับ
                block.className = 'absolute mx-1.5 p-3 rounded-2xl text-white shadow-md cursor-pointer hover:shadow-xl hover:scale-[1.03] hover:-translate-y-1 transition-all duration-300 overflow-hidden text-xs md:text-sm flex flex-col justify-center animate-pop-in border border-white/20';
                
                block.style.animationDelay = `${index * 0.05}s`; // Stagger effect
                block.style.left = `${(dayIndex * 14.28)}%`;
                block.style.width = '13.5%'; // ปรับให้เล็กลงนิดนึงจะได้ดูมีช่องไฟสวยๆ
                block.style.top = `${(startDec - START_HOUR) * PIXELS_PER_HOUR}px`;
                block.style.height = `${duration * PIXELS_PER_HOUR}px`;
                
                // ใช้สีแบบ Gradient เบาๆ
                block.style.background = `linear-gradient(135deg, ${cls.color}, ${adjustColorBrightness(cls.color, -20)})`;
                
                block.title = `สอนโดย: ${cls.teacher || 'ไม่ระบุ'}`;
                
                // เช็คความสูง ถ้าสูงพอก็ใส่ Icon
                const iconHtml = duration > 1.5 ? '<div class="absolute top-2 right-2 opacity-30"><i class="fas fa-book text-lg"></i></div>' : '';

                block.innerHTML = `
                    ${iconHtml}
                    <div class="font-bold truncate text-base drop-shadow-sm">${cls.name}</div>
                    <div class="opacity-95 text-xs truncate mt-0.5"><i class="fas fa-map-marker-alt text-[10px] mr-1"></i>${cls.room || ''}</div>
                    <div class="opacity-80 text-[10px] truncate font-mono mt-0.5 bg-black/10 inline-block px-1.5 py-0.5 rounded-md w-max">${cls.start} - ${cls.end}</div>
                `;
                block.onclick = () => openModal(cls);
                container.appendChild(block);
            });
        }

        // ฟังก์ชันช่วยปรับสีให้เข้มขึ้นเพื่อทำ Gradient
        function adjustColorBrightness(color, amount) {
            return '#' + color.replace(/^#/, '').replace(/../g, color => ('0'+Math.min(255, Math.max(0, parseInt(color, 16) + amount)).toString(16)).substr(-2));
        }

        function timeToDecimal(timeStr) {
            const [h, m] = timeStr.split(':').map(Number);
            return h + (m / 60);
        }

        document.getElementById('classForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const newClass = {
                name: document.getElementById('subjectName').value,
                teacher: document.getElementById('teacherName').value,
                day: document.getElementById('dayOfWeek').value,
                start: document.getElementById('startTime').value,
                end: document.getElementById('endTime').value,
                room: document.getElementById('room').value,
                color: document.getElementById('colorTag').value
            };

            if (timeToDecimal(newClass.start) >= timeToDecimal(newClass.end)) {
                alert("⚠️ เวลาเริ่มต้องน้อยกว่าเวลาสิ้นสุดนะ!");
                return;
            }

            try {
                const res = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(newClass)
                });
                const result = await res.json();
                
                if (result.status === 'success') {
                    loadData();
                    e.target.reset();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + result.message);
                }
            } catch (err) {
                console.error(err);
            }
        });

        async function resetData() {
            if(confirm('เตือนนะ! ข้อมูลจะหายหมดเลย ยืนยันไหม? 🗑️')) {
                await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'clear_all' })
                });
                loadData();
            }
        }

        async function deleteClassFromModal() {
            if (selectedClassId) {
                await fetch('api.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: selectedClassId })
                });
                closeModal();
                loadData();
            }
        }

        function openModal(cls) {
            selectedClassId = cls.id;
            document.getElementById('modalTitle').innerText = cls.name;
            document.getElementById('modalTeacher').innerText = cls.teacher || 'ไม่ระบุ';
            document.getElementById('modalTime').innerText = `${cls.start} - ${cls.end} (${dayNamesTh[cls.day]})`;
            document.getElementById('modalRoom').innerText = cls.room || 'ไม่ระบุห้อง';
            document.getElementById('infoModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('infoModal').classList.add('hidden');
            selectedClassId = null;
        }

        function updateStats() {
            // Animate counting numbers
            const totalClassesEl = document.getElementById('totalClasses');
            const totalHoursEl = document.getElementById('totalHours');
            
            const currentCount = parseInt(totalClassesEl.innerText);
            const newCount = scheduleData.length;
            
            // Simple animation
            totalClassesEl.innerText = newCount;
            
            let totalHours = 0;
            scheduleData.forEach(cls => totalHours += (timeToDecimal(cls.end) - timeToDecimal(cls.start)));
            totalHoursEl.innerText = `${totalHours.toFixed(1)} ชม.`;
        }
    </script>
</body>
</html>