// sidebar.js - IT Academy

var LESSONS = [
  { n:1,  file:"lesson1.html",  title:"1. \u0410\u043b\u0433\u043e\u0440\u0438\u0442\u043c \u0434\u0435\u0433\u0435\u043d \u043d\u0435?" },
  { n:2,  file:"lesson2.html",  title:"2. \u0410\u043b\u0433\u043e\u0440\u0438\u0442\u043c\u043d\u0456\u04a3 \u049b\u0430\u0441\u0438\u0435\u0442\u0442\u0435\u0440\u0456" },
  { n:3,  file:"lesson3.html",  title:"3. \u0410\u043b\u0433\u043e\u0440\u0438\u0442\u043c\u0434\u0456 \u0436\u0430\u0437\u0443 \u0442\u04d9\u0441\u0456\u043b\u0434\u0435\u0440\u0456" },
  { n:4,  file:"lesson4.html",  title:"4. \u0410\u043b\u0433\u043e\u0440\u0438\u0442\u043c \u0442\u04af\u0440\u043b\u0435\u0440\u0456" },
  { n:5,  file:"lesson5.html",  title:"5. \u0411\u0430\u0493\u0434\u0430\u0440\u043b\u0430\u043c\u0430\u043b\u0430\u0443 \u0434\u0435\u0433\u0435\u043d \u043d\u0435?" },
  { n:6,  file:"lesson6.html",  title:"6. \u0411\u0430\u0493\u0434\u0430\u0440\u043b\u0430\u043c\u0430\u043b\u0430\u0443 \u0442\u0456\u043b\u0434\u0435\u0440\u0456" },
  { n:7,  file:"lesson7.html",  title:"7. \u0410\u0439\u043d\u044b\u043c\u0430\u043b\u044b \u0436\u04d9\u043d\u0435 \u043c\u04d9\u043b\u0456\u043c\u0435\u0442 \u0442\u0438\u043f\u0442\u0435\u0440\u0456" },
  { n:8,  file:"lesson8.html",  title:"8. \u0415\u043d\u0433\u0456\u0437\u0443 \u0436\u04d9\u043d\u0435 \u0448\u044b\u0493\u0430\u0440\u0443" },
  { n:9,  file:"lesson9.html",  title:"9. \u0410\u0440\u0438\u0444\u043c\u0435\u0442\u0438\u043a\u0430\u043b\u044b\u049b \u043e\u043f\u0435\u0440\u0430\u0442\u043e\u0440\u043b\u0430\u0440" },
  { n:10, file:"lesson10.html", title:"10. \u0421\u0430\u043b\u044b\u0441\u0442\u044b\u0440\u0443 \u043e\u043f\u0435\u0440\u0430\u0442\u043e\u0440\u043b\u0430\u0440\u044b" },
  { n:11, file:"lesson11.html", title:"11. \u0428\u0430\u0440\u0442\u0442\u044b \u043e\u043f\u0435\u0440\u0430\u0442\u043e\u0440 (if/else)" },
  { n:12, file:"lesson12.html", title:"12. \u049a\u0430\u0439\u0442\u0430\u043b\u0430\u0443 \u043e\u043f\u0435\u0440\u0430\u0442\u043e\u0440\u044b (for)" },
  { n:13, file:"lesson13.html", title:"13. while \u0446\u0438\u043a\u043b\u0456" },
  { n:14, file:"lesson14.html", title:"14. \u0422\u0456\u0437\u0456\u043c\u0434\u0435\u0440 (List)" },
  { n:15, file:"lesson15.html", title:"15. \u049a\u043e\u0440\u044b\u0442\u044b\u043d\u0434\u044b \u0442\u0430\u043f\u0441\u044b\u0440\u043c\u0430\u043b\u0430\u0440" },
];

function getUser() {
  try { return JSON.parse(localStorage.getItem('ita_user')) || {}; }
  catch(e) { return {}; }
}

// Тест өткенде ғана шақырылады — келесі сабақты ашу үшін
function saveProgress(lessonNum) {
  var user = getUser();
  if (!user.id) return;
  var body = new URLSearchParams();
  body.append('user_id', user.id);
  body.append('lesson', lessonNum);
  fetch('save_progress.php', { method: 'POST', body: body }).catch(function(){});
}

// Бет жүктелгенде sidebar жасайды — saveProgress жоқ!
function buildSidebar(currentLesson) {
  var userId = (getUser().id) || 0;
  fetch('get_progress.php?user_id=' + userId)
    .then(function(r) { return r.json(); })
    .then(function(data) {
      var max = parseInt(data.max_lesson) || 1;
      renderSidebar(currentLesson, max);
    })
    .catch(function() {
      renderSidebar(currentLesson, 1);
    });
}

function renderSidebar(cur, max) {
  var wrap = document.querySelector('.sidebar-top');
  if (!wrap) return;
  var html = '<h2>Python Kursy</h2>';

  LESSONS.forEach(function(l) {
    if (l.n === cur) {
      // Қазіргі сабақ — active
      html += '<div class="lesson active"><div class="badge">' + l.n + '</div>' + l.title + '</div>';

    } else if (l.n < cur) {
      // Өткен сабақтар — жасыл ✓, басуға болады
      html += '<a href="' + l.file + '" style="text-decoration:none;color:inherit;">'
            + '<div class="lesson done">'
            + '<div class="badge" style="background:#22c55e;">&#10003;</div>'
            + l.title + '</div></a>';

    } else if (l.n <= max) {
      // Ашылған сабақтар (тест өтіп unlock болған)
      html += '<a href="' + l.file + '" style="text-decoration:none;color:inherit;">'
            + '<div class="lesson"><div class="badge">' + l.n + '</div>' + l.title + '</div></a>';

    } else {
      // Locked — әлі жетпеген
      html += '<div class="lesson locked" onclick="sidebarWarn()">'
            + '<div class="badge">' + l.n + '</div>' + l.title
            + '<div class="lock-icon">&#128274;</div></div>';
    }
  });

  wrap.innerHTML = html;
}

function sidebarWarn() {
  var el = document.getElementById('sbWarn');
  if (!el) {
    el = document.createElement('div');
    el.id = 'sbWarn';
    el.style.cssText = 'position:fixed;top:78px;right:22px;background:rgba(245,158,11,0.12);'
      + 'border:1px solid rgba(245,158,11,0.35);color:#fcd34d;padding:12px 18px;'
      + 'border-radius:12px;font-size:14px;font-weight:600;z-index:9999;'
      + 'backdrop-filter:blur(8px);transition:opacity 0.3s;';
    document.body.appendChild(el);
  }
  el.textContent = '\u0410\u043b\u0434\u044b\u04a3\u0493\u044b \u0441\u0430\u0431\u0430\u049b\u0442\u044b \u0430\u044f\u049b\u0442\u0430\u043c\u0430\u0439 \u0431\u04b1\u043b \u0441\u0430\u0431\u0430\u049b\u0442\u044b \u0430\u0448\u0443 \u043c\u04af\u043c\u043a\u0456\u043d \u0435\u043c\u0435\u0441!';
  el.style.opacity = '1';
  setTimeout(function() { el.style.opacity = '0'; }, 2800);
}

// ===== ОҚУ УАҚЫТЫН ЕСЕПТЕУ =====
(function(){
  var user = getUser();
  if(!user || !user.id) return;

  var startTime = Date.now();

  function saveTime(){
    var mins = Math.round((Date.now() - startTime) / 60000);
    if(mins < 1) return;
    var body = new URLSearchParams();
    body.append('user_id', user.id);
    body.append('minutes', mins);
    navigator.sendBeacon('save_study_time.php', body);
  }

  // Беттен кеткенде сақта
  window.addEventListener('beforeunload', saveTime);

  // Әр 5 минут сайын автоматты сақтау
  setInterval(function(){
    var mins = Math.round((Date.now() - startTime) / 60000);
    if(mins < 1) return;
    var body = new URLSearchParams();
    body.append('user_id', user.id);
    body.append('minutes', 1);
    fetch('save_study_time.php', {method:'POST', body:body}).catch(function(){});
    startTime = Date.now(); // таймерді қайта баста
  }, 5 * 60 * 1000);
})();
