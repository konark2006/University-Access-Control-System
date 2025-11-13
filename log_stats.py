import re
from collections import Counter, defaultdict

ACCESS_LOG = "/home/kkonark/logs/access.log"
ERROR_LOG = "/home/kkonark/logs/error.log"

# Regex that matches:
# IP | date | method | path | status | size | user-agent
log_pattern = re.compile(
    r'(?P<ip>\S+) - - \[(?P<date>[^:]+):\d+:\d+:\d+\] '
    r'"(?P<method>\S+) (?P<path>\S+) [^"]+" '
    r'(?P<status>\d+) (?P<size>\d+)'
    r'(?: "?(?P<agent>[^"]*)"?$)?'
)

page_count = Counter()
ip_count = Counter()
agent_count = Counter()
timeline = Counter()

with open(ACCESS_LOG, "r", errors="ignore") as f:
    for line in f:
        m = log_pattern.search(line)
        if m:
            ip = m.group("ip")
            date = m.group("date")
            path = m.group("path")
            agent = m.group("agent") or "Unknown"

            page_count[path] += 1
            ip_count[ip] += 1
            agent_count[agent] += 1
            timeline[date] += 1

print("\n===== PAGE ACCESS COUNT =====")
for page, cnt in page_count.most_common():
    print(f"{page}: {cnt}")

print("\n===== TOP IP ADDRESSES =====")
for ip, cnt in ip_count.most_common():
    print(f"{ip}: {cnt}")

print("\n===== BROWSER / USER AGENTS =====")
for agent, cnt in agent_count.most_common():
    print(f"{agent}: {cnt}")

print("\n===== ACCESS TIMELINE (per day) =====")
for date, cnt in timeline.items():
    print(f"{date}: {cnt}")

# -------- ERROR LOG ----------
print("\n===== ERROR TIMELINE =====")
error_timeline = Counter()

with open(ERROR_LOG, "r", errors="ignore") as f:
    for line in f:
        if "client" in line:
            m = re.search(r"\[client (\S+)\]", line)
            if m:
                error_timeline[m.group(1)] += 1

for ip, cnt in error_timeline.items():
    print(f"{ip}: {cnt}")

