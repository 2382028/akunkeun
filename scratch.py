import re

with open('resources/views/admin/templates/sidebar.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

def get_lines(start, end):
    return ''.join(lines[start-1:end])

blocks = {}
blocks[1] = get_lines(195, 202)
blocks[2] = get_lines(203, 320)
blocks[3] = get_lines(321, 365)
blocks[4] = get_lines(366, 415)
blocks[5] = get_lines(416, 557)

# Fix Block 6
block6_raw = get_lines(558, 568)
block6 = block6_raw.replace('<div class=\"accordion accordion-flush\" id=\"accordionFlushExample\">\n', '')
blocks[6] = block6

blocks[7] = get_lines(570, 579)
blocks[8] = get_lines(580, 605)

# Block 9 and 10 shared the if condition, so we split them
block9_content = get_lines(607, 636)
blocks[9] = "                @if (auth('administrator')->user()->role == 'Master')\n" + block9_content + "                @endif\n"

block10_content = get_lines(637, 661)
blocks[10] = "                @if (auth('administrator')->user()->role == 'Master')\n" + block10_content + "                @endif\n"

blocks[11] = get_lines(662, 693)
blocks[12] = get_lines(694, 701)
blocks[13] = get_lines(702, 711)

order = [1, 2, 3, 4, 8, 6, 7, 11, 10, 12, 9, 13]

new_content = ""
for n in order:
    new_content += blocks[n]

new_file_content = ''.join(lines[:194]) + new_content + ''.join(lines[711:])

with open('resources/views/admin/templates/sidebar.blade.php', 'w', encoding='utf-8') as f:
    f.write(new_file_content)

print('Success')
