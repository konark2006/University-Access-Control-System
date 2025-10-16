# UACS — HW04 Website & Corporate Design

**Project:** University Access Control System (UACS)  
**Team:** Konark, Sibel, Ashraya, Mubariz. 
**Live URL:** `http://10.60.36.1/~kkonark`  
**Repo path:** `Homework_4/`

## 1) Purpose
A small, hosted website that presents the UACS project with a consistent **Corporate Design (CD)** and a legally required **Imprint/Disclaimer**. Future homework will connect this UI to our MariaDB schema.

## 2) Corporate Design (CD)

**Logo**  
- PNG wordmark w/ shield-lock icon at top-left.  
- Default size: **44px** (responsive).  
- Clear space: ≥ ½ logo height.  
- File: `img/UACS_logo.png`

**Colors**  
- Primary Blue: `#0B3D91`  
- Accent Mint: `#0FB5A7`  
- Ink (text): `#111827`  
- Ink-2: `#374151`  
- Mist (bg band): `#F3F4F6`  
- White: `#FFFFFF`

**Typography**  
System sans stack (no external fonts):  
`-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif`

**Layout & Components**  
- Max width `1100px` container, 56–72px vertical rhythm.  
- Cards with 12px radius + soft shadow.  
- Sticky header w/ brand + nav; gradient hero (Blue→Mint) with CTAs.  
- Grids collapse to one column under 900px.  
- Keyboard-accessible mobile nav (aria attributes).

## 3) File Structure

```
Homework_4/
  index.html        # Homepage (hero, cards, steps, stats)
  imprint.html      # Imprint / Disclaimer page
  style.css         # Full site styles (tokens, layout, components, responsive)
  img/
    UACS_logo.png   # Header logo
  README.md         # This file
```

## 4) Run Locally (no install)
From the repo root:
```bash
cd Homework_4
python3 -m http.server 8000
# open http://localhost:8000/
```
VS Code users: right-click `index.html` → **Open with Live Server**.

## 5) Deploy to clabsql (live)

From your **server**:
```bash
cd ~/University-Access-Control-System
git pull
rsync -av --delete Homework_4/ ~/public_html/
chmod 755 ~/public_html ~/public_html/img
chmod 644 ~/public_html/*.html ~/public_html/style.css ~/public_html/img/* 2>/dev/null || true
# open http://10.60.36.1/~kkonark
```

### (Optional) Staging area
```bash
rsync -av --delete Homework_4/ ~/public_html_dev/
# preview at http://10.60.36.1/~kkonark/public_html_dev/
```

## 6) Imprint / Disclaimer (required)

**Contact**  
UACS Project Team  
Constructor University, Bremen, Germany  
Email: uacs-team at example dot edu

**Disclaimer**  
> This website is student lab work and does not necessarily reflect Constructor University opinions. Constructor University does not endorse this site, nor is it checked by Constructor University regularly, nor is it part of the official Constructor University web presence.  
>  
> For each external link existing on this website, we initially have checked that the target page does not contain contents which is illegal wrt. German jurisdiction. However, as we have no influence on such contents, this may change without our notice. Therefore we deny any responsibility for the websites referenced through our external links from here.  
>  
> No information conflicting with GDPR is stored in the server.

The **Imprint** is reachable in **one click** from every page (header + footer links).

## 7) Accessibility Notes
- High-contrast palette; semantic HTML (`<header>`, `<nav>`, `<main>`, `<footer>`).  
- Mobile nav button exposes `aria-controls` + `aria-expanded`.  
- Links keep visible focus/hover states.

## 8) Future Integration
- Add a small server-side API (PHP) on clabsql to read from `db_kkonark` (Requests, Resources, Events).  
- Replace static stats with live counts.  
- Admin login to approve/deny requests.

---

**Maintainer:** Konark • © 2025 UACS
