<?php

namespace Spatie\Prometheus\MetricTypes;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use Prometheus\CollectorRegistry;
use Prometheus\Gauge as PrometheusGauge;

class Gauge implements MetricType
{
    use Conditionable;

    protected array $values = [];

    public function __construct(
        protected string $label,
        null|float|Closure|array $value,
        protected ?string $name = null,
        protected ?string $namespace = null,
        protected ?string $helpText = null,
        protected ?array $labelNames = [],
        protected string $urlName = 'default',
    ) {
        $this->name = $name ?? Str::slug($this->label, '_');

        if (! is_null($value)) {
            $this->value($value);
        }

        $this->namespace = Str::of(config('prometheus.default_namespace'))
            ->slug('_')
            ->lower();
    }

    public function namespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function urlName(string $urlName): self
    {
        $this->urlName = $urlName;

        return $this;
    }

    public function getUrlName(): string
    {
        return $this->urlName;
    }

    public function label(string $label): self
    {
        $this->labelNames[] = $label;

        return $this;
    }

    public function labels(array $labelNames): self
    {
        $this->labelNames = $labelNames;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function helpText(string $helpText): self
    {
        $this->helpText = $helpText;

        return $this;
    }

    public function value(array|float|Closure $value, array|string $labelValues = []): self
    {
        $labelValues = Arr::wrap($labelValues);

        $this->values[] = [$value, $labelValues];

        return $this;
    }

    public function register(CollectorRegistry $registry): self
    {
        $gauge = $registry->getOrRegisterGauge(
            $this->namespace,
            $this->name,
            $this->helpText ?? '',
            $this->labelNames,
        );

        collect($this->values)
            ->each(function (array $valueAndLabels) use ($gauge) {
                $this->handleValueAndLabels($gauge, $valueAndLabels);
            });

        return $this;
    }

    protected function handleValueAndLabels(PrometheusGauge $gauge, array $valueAndLabels)
    {
        [$value, $labels] = $valueAndLabels;
        $value = value($value);

        if (is_array($value) && Arr::exists($value, 0) && is_array($value[0])) {
            foreach ($value as $valueAndLabels) {
                $this->handleValueAndLabels($gauge, $valueAndLabels);
            }

            return;
        }

        if (is_array($value) && count($value) === 0) {
            return;
        }

        if (is_array(($value))) {
            [$value, $labels] = $value;
        }

        $gauge->set($value, $labels);
    }
}
