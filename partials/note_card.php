<?php
// Partial: Note Card - dipakai di dashboard.php
$warna = $note['warna'] ?? 'white';
$kategori = $note['kategori'] ?? '';
$is_pinned = isset($note['pinned']) && $note['pinned'] == true;
$note_id = (string) $note['_id'];
$reminder = $note['reminder'] ?? '';
?>
<div class="note-card <?php echo $is_pinned ? 'pinned' : ''; ?> color-<?php echo htmlspecialchars($warna); ?>">
    <div class="note-header">
        <div class="note-title"><?php echo htmlspecialchars($note['judul']); ?></div>
    </div>
    <div class="note-body">
        <?php echo nl2br(htmlspecialchars($note['isi'])); ?>
    </div>
    <div class="note-footer">
        <div class="note-meta">
            <?php if ($kategori != ''): ?>
                <span class="note-kategori"><?php echo htmlspecialchars($kategori); ?></span>
            <?php endif; ?>
            <span class="note-date"><?php echo date('d M Y', strtotime($note['created_at'])); ?></span>
            <?php if ($reminder != ''): ?>
                <span class="note-reminder"><i class="bi bi-bell"></i>
                    <?php echo date('d M', strtotime($reminder)); ?></span>
            <?php endif; ?>
        </div>
        <div class="note-actions">
            <a href="pin_note.php?id=<?php echo $note_id; ?>" class="btn-pin"
                title="<?php echo $is_pinned ? 'Lepas Pin' : 'Pin'; ?>">
                <i class="bi <?php echo $is_pinned ? 'bi-pin-angle-fill' : 'bi-pin-angle'; ?>"></i>
            </a>
            <a href="edit_note.php?id=<?php echo $note_id; ?>" title="Edit">
                <i class="bi bi-pencil"></i>
            </a>
            <a href="hapus.php?id=<?php echo $note_id; ?>" class="btn-delete" title="Hapus"
                onclick="return confirm('Yakin ingin menghapus catatan ini?')">
                <i class="bi bi-trash3"></i>
            </a>
        </div>
    </div>
</div>