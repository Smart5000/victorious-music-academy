<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Gloock&family=Merriweather+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    [x-cloak] { display: none !important; }
    html { scroll-behavior: smooth; }
    body { background: #F8F6F2; color: #1C1F2F; font-family: 'Merriweather Sans', ui-sans-serif, system-ui, sans-serif; }
    h1, h2, h3, .font-display { font-family: 'Gloock', Georgia, serif; }
    .vvmi-page { min-height: 100vh; background: #F8F6F2; }
    .vvmi-container { width: 100%; max-width: 80rem; margin-left: auto; margin-right: auto; padding-left: 1rem; padding-right: 1rem; }
    @media (min-width: 640px) { .vvmi-container { padding-left: 1.5rem; padding-right: 1.5rem; } }
    @media (min-width: 1024px) { .vvmi-container { padding-left: 2rem; padding-right: 2rem; } }
    .vvmi-card { border-radius: 2rem; border: 1px solid rgba(81,60,199,.1); background: #fff; box-shadow: 0 18px 60px rgba(28,31,47,.08); transition: transform .3s ease, box-shadow .3s ease; }
    .vvmi-card:hover { transform: translateY(-.25rem); box-shadow: 0 24px 80px rgba(28,31,47,.12); }
    .vvmi-card-flat { border-radius: 2rem; border: 1px solid rgba(81,60,199,.1); background: #fff; box-shadow: 0 12px 40px rgba(28,31,47,.06); }
    .vvmi-button, .vvmi-button-primary, .vvmi-button-secondary, .vvmi-button-ghost { display: inline-flex; align-items: center; justify-content: center; border-radius: 9999px; padding: .75rem 1.5rem; font-size: .875rem; font-weight: 900; transition: transform .3s ease, background-color .3s ease; }
    .vvmi-button-primary { background: #513CC7; color: white; box-shadow: 0 10px 22px rgba(81,60,199,.25); }
    .vvmi-button-primary:hover { transform: translateY(-.125rem); filter: brightness(.96); }
    .vvmi-button-secondary { background: #513CC7; color: white; box-shadow: 0 10px 22px rgba(81,60,199,.25); }
    .vvmi-button-secondary:hover { transform: translateY(-.125rem); filter: brightness(.96); }
    .vvmi-button-ghost { background: white; color: #513CC7; box-shadow: inset 0 0 0 1px rgba(81,60,199,.15); }
    .vvmi-eyebrow { font-size: .75rem; font-weight: 900; text-transform: uppercase; letter-spacing: .22em; color: #513CC7; }
    .vvmi-heading { font-weight: 900; letter-spacing: -.025em; color: #1C1F2F; }
    .vvmi-body { line-height: 1.75rem; color: rgba(28,31,47,.8); }
    .vvmi-illustration { display: grid; place-items: center; border-radius: 1.5rem; background: #513CC7; color: #fff; box-shadow: inset 0 2px 12px rgba(28,31,47,.1); }
</style>
