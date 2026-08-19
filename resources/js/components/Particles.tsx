import { useEffect, useRef } from 'react';
import { Renderer, Program, Mesh, Color, Geometry } from 'ogl';

const VERT = `#version 300 es
precision highp float;

uniform float uTime;
uniform float uAspect;
uniform float uDrift;

in vec3 aPosition;
in vec3 aColor;
in float aRandom;

out vec3 vColor;
out float vAlpha;

void main() {
  vColor = aColor;

  float z = aPosition.z;
  float speed = 0.25 + aRandom * 0.75;

  vec2 pos = aPosition.xy;
  pos.x += sin(uTime * speed + aRandom * 6.28318) * uDrift * (0.4 + z);
  pos.y += cos(uTime * speed * 0.8 + aRandom * 6.28318) * uDrift * (0.4 + z);
  pos.x *= uAspect;

  gl_Position = vec4(pos, 0.0, 1.0);
  gl_PointSize = 6.0 + z * 8.0;

  vAlpha = 0.25 + (1.0 - z) * 0.75;
}
`;

const FRAG = `#version 300 es
precision highp float;

uniform float uAlpha;

in vec3 vColor;
in float vAlpha;

out vec4 fragColor;

void main() {
  vec2 uv = gl_PointCoord - 0.5;
  float d = length(uv);
  float glow = smoothstep(0.5, 0.0, d);
  float intensity = glow * glow;
  vec3 col = vColor * (1.0 + glow * 2.5);

  fragColor = vec4(col * intensity, intensity * vAlpha * uAlpha);
}
`;

interface ParticlesProps {
  count?: number;
  colors?: string[];
  alpha?: number;
  speed?: number;
  drift?: number;
}

export default function Particles(props: ParticlesProps) {
  const { colors = ['#60a5fa', '#818cf8', '#22d3ee', '#e0e7ff', '#ffffff'], alpha = 0.85, speed = 1.0, drift = 0.035 } = props;
  const count = props.count ?? 1500;
  const propsRef = useRef<ParticlesProps>(props);
  propsRef.current = props;

  const ctnDom = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const ctn = ctnDom.current;
    if (!ctn) return;

    const renderer = new Renderer({
      alpha: true,
      antialias: true,
    });
    const gl = renderer.gl;
    gl.clearColor(0, 0, 0, 0);
    gl.enable(gl.BLEND);
    gl.blendFunc(gl.ONE, gl.ONE);
    gl.canvas.style.backgroundColor = 'transparent';

    const palette = colors.map((hex: string) => {
      const c = new Color(hex);
      return [c.r, c.g, c.b];
    });

    const positions = new Float32Array(count * 3);
    const colorData = new Float32Array(count * 3);
    const randoms = new Float32Array(count);

    for (let i = 0; i < count; i++) {
      positions[i * 3] = Math.random() * 2 - 1;
      positions[i * 3 + 1] = Math.random() * 2 - 1;
      positions[i * 3 + 2] = Math.random();
      randoms[i] = Math.random();
      const c = palette[(Math.random() * palette.length) | 0];
      colorData[i * 3] = c[0];
      colorData[i * 3 + 1] = c[1];
      colorData[i * 3 + 2] = c[2];
    }

    const geometry = new Geometry(gl, {
      aPosition: { size: 3, data: positions },
      aColor: { size: 3, data: colorData },
      aRandom: { size: 1, data: randoms },
    });

    let program: Program | undefined;

    function resize() {
      if (!ctn) return;
      const width = ctn.offsetWidth;
      const height = ctn.offsetHeight;
      renderer.setSize(width, height);
      if (program) {
        program.uniforms.uAspect.value = width / Math.max(height, 1);
      }
    }
    window.addEventListener('resize', resize);

    program = new Program(gl, {
      vertex: VERT,
      fragment: FRAG,
      transparent: true,
      depthTest: false,
      depthWrite: false,
      uniforms: {
        uTime: { value: 0 },
        uAspect: { value: ctn.offsetWidth / Math.max(ctn.offsetHeight, 1) },
        uAlpha: { value: alpha },
        uDrift: { value: drift },
      },
    });

    const mesh = new Mesh(gl, { geometry, program, mode: gl.POINTS });
    ctn.appendChild(gl.canvas);

    let animateId = 0;
    const update = (t: number) => {
      animateId = requestAnimationFrame(update);
      const { speed = 1.0, alpha = 0.85, drift = 0.035 } = propsRef.current;
      if (program) {
        program.uniforms.uTime.value = t * 0.001 * speed;
        program.uniforms.uAlpha.value = alpha;
        program.uniforms.uDrift.value = drift;
        renderer.render({ scene: mesh });
      }
    };
    animateId = requestAnimationFrame(update);

    resize();

    return () => {
      cancelAnimationFrame(animateId);
      window.removeEventListener('resize', resize);
      if (ctn && gl.canvas.parentNode === ctn) {
        ctn.removeChild(gl.canvas);
      }
      geometry.remove();
      program?.remove();
      gl.getExtension('WEBGL_lose_context')?.loseContext();
    };
  }, [alpha, drift, count]);

  return <div ref={ctnDom} className="w-full h-full" />;
}
