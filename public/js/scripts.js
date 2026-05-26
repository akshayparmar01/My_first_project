// animate numbers and show temporary toast if query param exists
document.addEventListener('DOMContentLoaded', ()=> {
  document.querySelectorAll('.stat .big').forEach(el=>{
    const val = parseInt(el.textContent) || 0;
    let cur = 0;
    const step = Math.max(1, Math.floor(val/30));
    const t = setInterval(()=>{
      cur += step;
      if (cur >= val) { el.textContent = val; clearInterval(t); }
      else el.textContent = cur;
    },16);
  });
  if (window.location.search.indexOf('returned=1') !== -1) {
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = 'Book returned successfully';
    document.body.appendChild(toast);
    setTimeout(()=>{ toast.style.display='block'; },200);
    setTimeout(()=>{ toast.style.display='none'; toast.remove(); },2500);
  }
  // Password show/hide toggle
  window.togglePassword = function(id, el) {
    const input = document.getElementById(id);
    if (!input) return;
    if (input.type === 'password') {
      input.type = 'text';
      el.innerHTML = `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.81 21.81 0 0 1 5.06-6.06M1 1l22 22"/><path d="M9.53 9.53A3 3 0 0 0 12 15a3 3 0 0 0 2.47-5.47"/></svg>`;
    } else {
      input.type = 'password';
      el.innerHTML = `<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
    }
  }
});
