import re
from collections import Counter
import matplotlib.pyplot as plt

ACCESS_LOG = "/home/kkonark/logs/access.log"
ERROR_LOG = "/home/kkonark/logs/error.log"

# ---- Regex for parsing ----
log_pattern = re.compile(
    r'(?P<ip>\S+) - - \[(?P<date>[^:]+):\d+:\d+:\d+\] '
    r'"(?P<method>\S+) (?P<path>\S+) [^"]+" '
    r'(?P<status>\d+) (?P<size>\d+)'
)

# ---- Counters ----
page_count = Counter()
timeline = Counter()
error_count = Counter()

# ---- Read Access Log ----
with open(ACCESS_LOG, "r", errors="ignore") as f:
    for line in f:
        m = log_pattern.search(line)
        if m:
            page_count[m.group("path")] += 1
            timeline[m.group("date")] += 1

# ---- Read Error Log ----
with open(ERROR_LOG, "r", errors="ignore") as f:
    for line in f:
        if "client" in line:
            m = re.search(r"\[client (\S+)\]", line)
            if m:
                error_count[m.group(1)] += 1

# ======================================================
# 1. PAGE ACCESS COUNT (BAR CHART)
# ======================================================
plt.figure(figsize=(10,5))
pages = list(page_count.keys())
counts = list(page_count.values())
plt.barh(pages, counts, color="skyblue")
plt.xlabel("Hits")
plt.title("Page Access Count")
plt.tight_layout()
plt.savefig("page_access_count.png")
plt.close()

# ======================================================
# 2. ACCESS TIMELINE PER DAY (LINE CHART)
# ======================================================
plt.figure(figsize=(10,5))
dates = list(timeline.keys())
values = list(timeline.values())
plt.plot(dates, values, marker="o", linewidth=2)
plt.xlabel("Date")
plt.ylabel("Requests")
plt.title("Access Timeline (Per Day)")
plt.grid(True, alpha=0.3)
plt.tight_layout()
plt.savefig("access_timeline.png")
plt.close()

# ======================================================
# 3. ERROR TIMELINE BY IP (BAR CHART)
# ======================================================
plt.figure(figsize=(10,5))
ips = list(error_count.keys())
errors = list(error_count.values())
plt.bar(ips, errors, color="salmon")
plt.xlabel("IP Address")
plt.ylabel("Errors")
plt.title("Error Count by IP")
plt.tight_layout()
plt.savefig("error_count.png")
plt.close()

print("✅ Charts generated:")
print(" - page_access_count.png")
print(" - access_timeline.png")
print(" - error_count.png")
