# 🎨 UX Optimization Proposals for Full HD (1920px+)

Based on current best practices for developer documentation and reference tools (e.g., Stripe Docs, MDN, DevDocs), here are 3 proposals to optimize the G-code reference for large screens.

---

## 🏗️ Proposal 1: "The Cockpit" (Enhanced 3-Column)
*Best for: Power users who want to see everything at once.*

This is an evolution of the current layout, fully utilizing the 1920px width.

### **Layout:**
- **Left (TOC):** **380px** (Fixed). Searchable tree view of G-codes.
- **Center (Search & Context):** **Flex (fluid)**. Main search bar prominent at the top. Below it, a "Quick Reference Grid" or the filtered results.
- **Right (Inspector):** **500px** (Fixed). A powerful detail inspector.

### **Key Features:**
- **Persistent Inspector:** The right panel isn't just for explanations. It shows parameter details, extensive examples, and "Related Codes" without navigating away.
- **Keyboard Navigation:** Arrow keys move selection in the center, right panel updates instantly (like MacOS Finder).
- **Search Highlighting:** Search results highlight matches in the code examples in the right panel immediately.

**✅ Pros:** Highest information density. Very fast lookup.
**❌ Cons:** Can feel "busy" if not visually clean.

---

## 📖 Proposal 2: "The Linear Reader" (2-Column Focus)
*Best for: Reading and learning linear sequences.*

Moves away from the "App" feel towards a "Documentation" feel.

### **Layout:**
- **Left (Navigation):** **320px**. Compact sidebar.
- **Right (Content):** **Max-width 900px + Centered**.

### **Key Features:**
- **Inline Details:** Instead of a separate panel, clicking a G-code expands it (Accordion style) or jumps to a section where all details are inline.
- **Readable Typography:** Larger fonts, more whitespace, optimized for reading long descriptions.
- **Floating TOC:** The Table of Contents marks your reading position as you scroll.

**✅ Pros:** Excellent readability. Less eye movement (left-right-left).
**❌ Cons:** Slower for random access/lookup comparisons.

---

## 🔎 Proposal 3: "The Spotlight" (Search-First)
*Best for: Rapidly finding 1 specific thing.*

Similar to macOS Spotlight or VS Code Command Palette.

### **Layout:**
- **Initial State:** Huge, centered Search Bar on a clean background.
- **Active State:**
    - Search results appear in a masonry grid below.
    - Clicking a result opens a **Modal Overlay** or **Slide-over Panel** from the right.

### **Key Features:**
- **Focus:** No clutter. Just search.
- **Preview Cards:** Search results show "Code + Short Summary + Most used Params" directly on the card.
- **Immersive Details:** The detail view overlays the content, allowing it to use almost full screen focused attention when needed.

**✅ Pros:** Extremely modern, clean, mobile-friendly logic scales up well.
**❌ Cons:** Loses the "Reference List" overview.

---

## 💡 Recommendation
For a G-code Reference, **Proposal 1 (The Cockpit)** is usually the most efficient because users often need to compare G0 vs G1, or check 3 parameters quickly.

**Optimizations implemented in v2.0.5:**
- Moved towards **Proposal 1**:
- Increased TOC to 360px.
- Increased Inspector to 480px.
- Added 24px gap for breathing room.
- Modernized styling (Shadows, Borders, Typography).
