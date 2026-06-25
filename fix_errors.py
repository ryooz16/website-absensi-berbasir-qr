import re

def fix_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Fix \' to '
    content = content.replace("\\'", "'")

    # 2. Fix duplicate search blocks in guruExport and guruIndex
    content = re.sub(
        r'(if \(\$search\) \{\s*\$queryRaw->whereHas\(\'guru\', function \(\$q\) use \(\$search\) \{\s*\$q->where\(\'name\', \'like\', "%\{\$search\}%"\);\s*\}\);\s*\}\s*)+',
        r'if ($search) {\n            $queryRaw->whereHas(\'guru\', function ($q) use ($search) {\n                $q->where(\'name\', \'like\', "%{$search}%");\n            });\n        }\n            \n        ',
        content
    )

    # 3. Fix duplicate $search = $request->search;
    content = re.sub(
        r'(\$search = \$request->search;\s*)+',
        r'$search = $request->search;\n\n        ',
        content
    )

    # 4. Fix compact duplicate 'search'
    content = content.replace("'search', 'kelasId', 'mapelId', 'search',", "'search', 'kelasId', 'mapelId',")

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

fix_file('app/Http/Controllers/Admin/LaporanController.php')
fix_file('app/Http/Controllers/KepalaSekolah/LaporanController.php')
print("Fixed")
