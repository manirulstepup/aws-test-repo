const fs = require('fs')
const path = require('path')

let raw = ''
process.stdin.setEncoding('utf8')
process.stdin.on('data', (chunk) => {
  raw += chunk
})
process.stdin.on('end', () => {
  try {
    const input = raw ? JSON.parse(raw) : {}
    const logDir = path.join(__dirname, '..', 'logs')
    fs.mkdirSync(logDir, { recursive: true })
    const entry = {
      timestamp: new Date().toISOString(),
      session_id: input.session_id || null,
      cwd: input.cwd || process.cwd(),
      prompt: input.prompt || '',
    }
    fs.appendFileSync(
      path.join(logDir, 'prompts.jsonl'),
      JSON.stringify(entry) + '\n',
      'utf8'
    )
  } catch (err) {
    process.stderr.write('log-prompt hook: ' + err.message + '\n')
  }
  process.exit(0)
})
