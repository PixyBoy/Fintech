# فایل: tree.ps1  (UTF-8 ذخیره کن)
$Exclude = @('vendor','storage', '.vscode', '.git')

# خطوط رو جمع می‌کنیم که در پایان یکجا بنویسیم
$lines = New-Object System.Collections.Generic.List[string]
$lines.Add( (Split-Path -Leaf (Get-Location)) )

function Show-Tree {
    param([string]$Path, [string]$Prefix = '')

    $items = Get-ChildItem -LiteralPath $Path -Force |
             Where-Object { $Exclude -notcontains $_.Name } |
             Sort-Object { -not $_.PSIsContainer }, Name   # اول پوشه‌ها، بعد فایل‌ها

    for ($i=0; $i -lt $items.Count; $i++) {
        $item   = $items[$i]
        $isLast = ($i -eq $items.Count - 1)
        $connector = if ($isLast) { '\-- ' } else { '|-- ' }  # فقط ASCII

        $lines.Add("$Prefix$connector$($item.Name)")

        if ($item.PSIsContainer) {
            $newPrefix = $Prefix + (if ($isLast) { '    ' } else { '|   ' })
            Show-Tree -Path $item.FullName -Prefix $newPrefix
        }
    }
}

Show-Tree -Path .
$lines | Set-Content -Encoding utf8 -NoNewline:$false -Path tree.txt
Write-Host "Wrote tree.txt"
