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
});
