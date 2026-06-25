def fix_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # The actual string to look for is: \'
    content = content.replace("\\'", "'")

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

fix_file('app/Http/Controllers/Admin/LaporanController.php')
fix_file('app/Http/Controllers/KepalaSekolah/LaporanController.php')
print("Fixed")
