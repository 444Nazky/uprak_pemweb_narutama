"""Membuat diagram flowchart dan ERD untuk dokumen perancangan."""
from pathlib import Path

OUT = Path(__file__).parent
INK = "#101e18"
MUTED = "#5b6b64"
LINE = "#9fb0a4"
PRIMARY = "#14513f"
SOFT = "#e6efe8"
PAPER = "#f4f6f2"
WHITE = "#ffffff"
WARN_BG = "#fbf0d7"
WARN = "#7c5300"


def esc(value):
    return (value.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;"))


def text(x, y, value, size=15, weight=500, color=INK, anchor="middle", spacing=0):
    return (
        f'<text x="{x}" y="{y}" font-family="Plus Jakarta Sans, DejaVu Sans, sans-serif" '
        f'font-size="{size}" font-weight="{weight}" fill="{color}" text-anchor="{anchor}" '
        f'letter-spacing="{spacing}">{esc(value)}</text>'
    )


def box(x, y, w, h, label, sub=None, fill=WHITE, stroke=LINE, rx=10, size=15, weight=600, color=INK):
    parts = [
        f'<rect x="{x}" y="{y}" width="{w}" height="{h}" rx="{rx}" fill="{fill}" stroke="{stroke}" stroke-width="1.5"/>'
    ]
    if sub:
        parts.append(text(x + w / 2, y + h / 2 - 4, label, size, weight, color))
        parts.append(text(x + w / 2, y + h / 2 + 16, sub, 12.5, 500, MUTED))
    else:
        parts.append(text(x + w / 2, y + h / 2 + size * 0.35, label, size, weight, color))
    return "".join(parts)


def terminal(x, y, w, h, label):
    return box(x, y, w, h, label, fill=PRIMARY, stroke=PRIMARY, rx=h / 2, color=WHITE)


def diamond(cx, cy, w, h, label):
    half_w, half_h = w / 2, h / 2
    points = f"{cx},{cy - half_h} {cx + half_w},{cy} {cx},{cy + half_h} {cx - half_w},{cy}"
    return (
        f'<polygon points="{points}" fill="{WARN_BG}" stroke="{WARN}" stroke-width="1.5"/>'
        + text(cx, cy + 5, label, 14.5, 700, WARN)
    )


def arrow(points, label=None, label_pos=None, dashed=False):
    d = "M " + " L ".join(f"{x} {y}" for x, y in points)
    dash = ' stroke-dasharray="6 5"' if dashed else ""
    svg = (
        f'<path d="{d}" fill="none" stroke="{LINE}" stroke-width="1.8" '
        f'marker-end="url(#arrow)"{dash}/>'
    )
    if label and label_pos:
        svg += text(label_pos[0], label_pos[1], label, 12.5, 700, MUTED)
    return svg


def frame(width, height, title, subtitle):
    return (
        f'<svg xmlns="http://www.w3.org/2000/svg" width="{width}" height="{height}" '
        f'viewBox="0 0 {width} {height}">'
        "<defs>"
        f'<marker id="arrow" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="7" markerHeight="7" orient="auto">'
        f'<path d="M 0 0 L 10 5 L 0 10 z" fill="{LINE}"/></marker>'
        "</defs>"
        f'<rect width="{width}" height="{height}" fill="{PAPER}"/>'
        + text(60, 58, title, 26, 800, INK, anchor="start", spacing=-0.6)
        + text(60, 86, subtitle, 14, 500, MUTED, anchor="start")
        + f'<line x1="60" y1="104" x2="{width - 60}" y2="104" stroke="#d7ded4" stroke-width="1"/>'
    )


def flowchart_admin():
    width, height = 1100, 900
    svg = [frame(width, height, "Flowchart 1: Akses Dashboard Admin", "Sistem Kotak Aspirasi Sekolah, dua titik keputusan: status login dan peran pengguna")]
    svg.append(terminal(460, 130, 180, 52, "Mulai"))
    svg.append(box(380, 212, 340, 64, "Buka /admin/dashboard"))
    svg.append(diamond(550, 356, 280, 128, "Sudah login?"))
    svg.append(box(820, 324, 230, 64, "Redirect ke /login", fill=WHITE))
    svg.append(terminal(845, 428, 180, 52, "Selesai"))
    svg.append(diamond(550, 540, 300, 128, "Role = admin?"))
    svg.append(box(830, 508, 220, 64, "Halaman 403", "Akses ditolak"))
    svg.append(box(360, 650, 380, 64, "Tampilkan dashboard", "Statistik aspirasi real-time"))
    svg.append(terminal(460, 756, 180, 52, "Selesai"))

    svg.append(arrow([(550, 182), (550, 212)]))
    svg.append(arrow([(550, 276), (550, 292)]))
    svg.append(arrow([(690, 356), (820, 356)], "Tidak", (748, 344)))
    svg.append(arrow([(935, 388), (935, 428)]))
    svg.append(arrow([(550, 420), (550, 476)], "Ya", (566, 452)))
    svg.append(arrow([(700, 540), (830, 540)], "Tidak", (758, 528)))
    svg.append(arrow([(550, 604), (550, 650)], "Ya", (566, 632)))
    svg.append(arrow([(550, 714), (550, 756)]))
    svg.append(
        f'<rect x="790" y="786" width="250" height="54" rx="8" fill="{SOFT}"/>'
        + text(805, 808, "Middleware: auth, admin", 12.5, 700, PRIMARY, anchor="start")
        + text(805, 826, "Rute dilindungi sebelum controller", 11.5, 500, PRIMARY, anchor="start")
    )
    svg.append("</svg>")
    return "".join(svg)


def flowchart_kirim():
    width, height = 1150, 1140
    svg = [frame(width, height, "Flowchart 2: Kirim Aspirasi", "Alur pengisian formulir, validasi server, unggah foto, dan penyimpanan data")]
    svg.append(terminal(485, 130, 180, 52, "Mulai"))
    svg.append(box(395, 212, 360, 60, "Buka /aspirasi/create", "Form kosong + daftar kategori"))
    svg.append(box(355, 302, 440, 76, "Isi data laporan", "Judul, kategori, lokasi, pesan, prioritas"))
    svg.append(box(410, 408, 330, 60, "Kirim POST /aspirasi"))
    svg.append(diamond(575, 550, 300, 130, "Validasi lolos?"))
    svg.append(box(110, 518, 250, 64, "Tampilkan error", "Pertahankan input old()"))
    svg.append(diamond(575, 730, 280, 120, "Ada foto?"))
    svg.append(box(770, 698, 330, 64, "Simpan ke penyimpanan", "storage/app/public/bukti"))
    svg.append(box(375, 838, 400, 64, "Simpan aspirasi", "Status awal: menunggu"))
    svg.append(box(345, 928, 460, 64, "Redirect ke detail laporan", "Flash message sukses"))
    svg.append(terminal(485, 1028, 180, 52, "Selesai"))

    svg.append(arrow([(575, 182), (575, 212)]))
    svg.append(arrow([(575, 272), (575, 302)]))
    svg.append(arrow([(575, 378), (575, 408)]))
    svg.append(arrow([(575, 468), (575, 485)]))
    svg.append(arrow([(425, 550), (360, 550)], "Tidak", (392, 537)))
    svg.append(arrow([(235, 518), (235, 340), (355, 340)], "Ulangi input", (245, 430)))
    svg.append(arrow([(575, 615), (575, 670)], "Ya", (591, 646)))
    svg.append(arrow([(715, 730), (770, 730)], "Ya", (736, 718)))
    svg.append(arrow([(935, 762), (935, 870), (775, 870)]))
    svg.append(arrow([(575, 790), (575, 838)], "Tidak", (591, 820)))
    svg.append(arrow([(575, 902), (575, 928)]))
    svg.append(arrow([(575, 992), (575, 1028)]))

    svg.append(
        f'<rect x="60" y="130" width="270" height="190" rx="10" fill="{WHITE}" stroke="#d7ded4"/>'
        + text(78, 160, "Aturan validasi", 12.5, 800, PRIMARY, anchor="start", spacing=0.8)
        + text(78, 186, "judul: wajib, min 5", 12.5, 500, MUTED, anchor="start")
        + text(78, 208, "kategori_id: wajib, exists", 12.5, 500, MUTED, anchor="start")
        + text(78, 230, "lokasi: wajib, min 3", 12.5, 500, MUTED, anchor="start")
        + text(78, 252, "pesan: wajib, min 20", 12.5, 500, MUTED, anchor="start")
        + text(78, 274, "foto: jpg/png, maks 2 MB", 12.5, 500, MUTED, anchor="start")
        + text(78, 296, "prioritas: rendah/sedang/tinggi", 12.5, 500, MUTED, anchor="start")
    )
    svg.append("</svg>")
    return "".join(svg)


def erd():
    width, height = 1180, 1160
    svg = [frame(width, height, "ERD: Relasi Tabel Sistem Kotak Aspirasi", "Satu pengguna memiliki banyak aspirasi, satu kategori menaungi banyak aspirasi")]

    def table(x, y, w, name, rows, height):
        parts = [
            f'<rect x="{x}" y="{y}" width="{w}" height="{height}" rx="10" fill="{WHITE}" stroke="{LINE}" stroke-width="1.5"/>',
            f'<path d="M {x} {y + 10} q 0 -10 10 -10 h {w - 20} q 10 0 10 10 v 34 h -{w} z" fill="{PRIMARY}"/>',
            text(x + 16, y + 30, name, 15, 700, WHITE, anchor="start"),
        ]
        for index, (col, meta) in enumerate(rows):
            row_y = y + 62 + index * 32
            key = meta in ("PK", "FK")
            if key:
                parts.append(f'<rect x="{x + 14}" y="{row_y - 14}" width="26" height="18" rx="4" fill="{SOFT}"/>')
                parts.append(text(x + 27, row_y, meta, 10.5, 800, PRIMARY))
            parts.append(text(x + 50, row_y, col, 13, 600 if key else 500, INK, anchor="start"))
            if index < len(rows) - 1:
                parts.append(f'<line x1="{x}" y1="{row_y + 13}" x2="{x + w}" y2="{row_y + 13}" stroke="#edf1ec"/>')
        return "".join(parts)

    svg.append(table(70, 140, 320, "users", [
        ("id", "PK"), ("name", ""), ("email (unique)", ""), ("password", ""),
        ("kelas", ""), ("role (siswa/admin)", ""), ("timestamps", ""),
    ], 300))
    svg.append(table(790, 140, 320, "kategoris", [
        ("id", "PK"), ("nama (unique)", ""), ("slug (unique)", ""), ("keterangan", ""), ("timestamps", ""),
    ], 238))
    svg.append(table(400, 520, 380, "aspirasis", [
        ("id", "PK"), ("user_id", "FK"), ("kategori_id", "FK"), ("judul", ""), ("lokasi", ""),
        ("pesan", ""), ("foto (nullable)", ""), ("status (enum)", ""), ("prioritas (enum)", ""),
        ("tanggapan (nullable)", ""), ("ditanggapi_pada", ""), ("timestamps", ""),
    ], 452))

    # users.id (1) -> aspirasis.user_id (N)
    svg.append(arrow([(390, 330), (470, 330), (470, 520)]))
    svg.append(text(404, 318, "1", 14, 800, PRIMARY))
    svg.append(text(484, 508, "N", 14, 800, PRIMARY))

    # kategoris.id (1) -> aspirasis.kategori_id (N)
    svg.append(arrow([(790, 330), (700, 330), (700, 520)]))
    svg.append(text(776, 318, "1", 14, 800, PRIMARY))
    svg.append(text(686, 508, "N", 14, 800, PRIMARY))

    svg.append(
        f'<rect x="70" y="1020" width="1010" height="76" rx="10" fill="{SOFT}"/>'
        + text(90, 1048, "Keterangan", 12.5, 800, PRIMARY, anchor="start", spacing=0.8)
        + text(90, 1072, "FK users.id ke aspirasis.user_id memakai cascade on delete. FK kategoris.id memakai restrict on delete agar kategori yang sudah dipakai tidak hilang.", 12.5, 500, PRIMARY, anchor="start")
    )

    svg.append("</svg>")
    return "".join(svg)


if __name__ == "__main__":
    for name, content in [
        ("flowchart-akses-admin", flowchart_admin()),
        ("flowchart-kirim-aspirasi", flowchart_kirim()),
        ("erd", erd()),
    ]:
        (OUT / f"{name}.svg").write_text(content)
        print(name, "ok")
