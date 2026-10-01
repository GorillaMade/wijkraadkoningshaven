export type TeamMember = { name:string; role:string; gender:'man'|'vrouw'; image?:string; imageAlt?:string };
export const team: TeamMember[] = [
  {name:'Joost',role:'Voorzitter',gender:'man'},
  {name:'Anja',role:'Secretaris',gender:'vrouw'},
  {name:'Ruud',role:'Penningmeester',gender:'man'},
  {name:'Paul',role:'Commissielid',gender:'man'},
  {name:'Nick',role:'Commissielid',gender:'man'},
];
